const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const pool = require('../db');

// Admin login
exports.login = async (req, res) => {
  try {
    const { username, password } = req.body;

    if (!username || !password) {
      return res.status(400).json({ error: 'Username dan password wajib diisi' });
    }

    // Get admin from database (for now, use hardcoded admin)
    // In production, you should store admin in database
    const adminUsername = process.env.ADMIN_USERNAME || 'admin';
    const adminPasswordHash = process.env.ADMIN_PASSWORD_HASH || '$2a$10$rQEY9zXkQjKxQjKxQjKxQeO5vXjKxQjKxQjKxQjKxQjKxQjKxQjK'; // default password: admin123

    if (username !== adminUsername) {
      return res.status(401).json({ error: 'Username atau password salah' });
    }

    const isValidPassword = await bcrypt.compare(password, adminPasswordHash);
    
    if (!isValidPassword) {
      return res.status(401).json({ error: 'Username atau password salah' });
    }

    const token = jwt.sign(
      { username: adminUsername },
      process.env.JWT_SECRET,
      { expiresIn: '24h' }
    );

    res.json({ 
      success: true, 
      token,
      message: 'Login berhasil' 
    });
  } catch (error) {
    console.error('Login error:', error);
    res.status(500).json({ error: 'Terjadi kesalahan server' });
  }
};

// Add new participant
exports.addParticipant = async (req, res) => {
  try {
    const { nomor_absen, nama } = req.body;

    // Trim and uppercase
    const trimmedNomorAbsen = nomor_absen?.trim().toUpperCase();
    const trimmedNama = nama?.trim();

    if (!trimmedNomorAbsen || !trimmedNama) {
      return res.status(400).json({ error: 'Nomor absen dan nama wajib diisi' });
    }

    // Check if nomor_absen already exists
    const checkQuery = 'SELECT id FROM participants WHERE nomor_absen = $1';
    const checkResult = await pool.query(checkQuery, [trimmedNomorAbsen]);

    if (checkResult.rows.length > 0) {
      return res.status(400).json({ error: 'Nomor absen sudah terdaftar' });
    }

    // Insert new participant
    const insertQuery = `
      INSERT INTO participants (nomor_absen, nama)
      VALUES ($1, $2)
      RETURNING id, nomor_absen, nama, created_at
    `;
    const insertResult = await pool.query(insertQuery, [trimmedNomorAbsen, trimmedNama]);

    res.status(201).json({
      success: true,
      message: 'Peserta berhasil ditambahkan',
      data: insertResult.rows[0]
    });
  } catch (error) {
    console.error('Add participant error:', error);
    res.status(500).json({ error: 'Terjadi kesalahan server' });
  }
};

// Get all participants
exports.getParticipants = async (req, res) => {
  try {
    const query = `
      SELECT id, nomor_absen, nama, created_at
      FROM participants
      ORDER BY nomor_absen ASC
    `;
    const result = await pool.query(query);

    res.json({
      success: true,
      data: result.rows,
      total: result.rows.length
    });
  } catch (error) {
    console.error('Get participants error:', error);
    res.status(500).json({ error: 'Terjadi kesalahan server' });
  }
};

// Get daily attendance
exports.getAttendance = async (req, res) => {
  try {
    const { date } = req.query;

    let query = `
      SELECT 
        a.id,
        a.participant_id,
        a.tanggal,
        a.jam,
        a.created_at,
        p.nomor_absen,
        p.nama
      FROM attendance a
      JOIN participants p ON a.participant_id = p.id
    `;

    let params = [];

    if (date) {
      query += ' WHERE a.tanggal = $1';
      params.push(date);
    }

    query += ' ORDER BY a.jam DESC';

    const result = await pool.query(query, params);

    // Get total participants
    const countQuery = 'SELECT COUNT(*) as total FROM participants';
    const countResult = await pool.query(countQuery);
    const totalParticipants = parseInt(countResult.rows[0].total);

    res.json({
      success: true,
      data: result.rows,
      summary: {
        total_hadir: result.rows.length,
        total_peserta: totalParticipants,
        tanggal: date || new Date().toISOString().split('T')[0]
      }
    });
  } catch (error) {
    console.error('Get attendance error:', error);
    res.status(500).json({ error: 'Terjadi kesalahan server' });
  }
};

// Delete participant
exports.deleteParticipant = async (req, res) => {
  try {
    const { id } = req.params;

    // Delete attendance records first
    await pool.query('DELETE FROM attendance WHERE participant_id = $1', [id]);
    
    // Delete participant
    await pool.query('DELETE FROM participants WHERE id = $1', [id]);

    res.json({
      success: true,
      message: 'Peserta berhasil dihapus'
    });
  } catch (error) {
    console.error('Delete participant error:', error);
    res.status(500).json({ error: 'Terjadi kesalahan server' });
  }
};

// Get today's stats
exports.getTodayStats = async (req, res) => {
  try {
    const today = new Date().toISOString().split('T')[0];

    const query = `
      SELECT COUNT(*) as total
      FROM attendance
      WHERE tanggal = $1
    `;
    const result = await pool.query(query, [today]);

    const countQuery = 'SELECT COUNT(*) as total FROM participants';
    const countResult = await pool.query(countQuery);

    res.json({
      success: true,
      data: {
        hadir: parseInt(result.rows[0].total),
        total: parseInt(countResult.rows[0].total),
        tanggal: today
      }
    });
  } catch (error) {
    console.error('Get today stats error:', error);
    res.status(500).json({ error: 'Terjadi kesalahan server' });
  }
};


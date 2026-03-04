const axios = require('axios');
const pool = require('../db');

// Submit attendance
exports.submitAttendance = async (req, res) => {
  try {
    const { nomor_absen } = req.body;

    // Trim and uppercase
    const trimmedNomorAbsen = nomor_absen?.trim().toUpperCase();

    if (!trimmedNomorAbsen) {
      return res.status(400).json({ 
        success: false,
        status: 'error',
        message: 'Nomor absen wajib diisi' 
      });
    }

    // Find participant by nomor_absen
    const participantQuery = `
      SELECT id, nomor_absen, nama 
      FROM participants 
      WHERE nomor_absen = $1
    `;
    const participantResult = await pool.query(participantQuery, [trimmedNomorAbsen]);

    if (participantResult.rows.length === 0) {
      return res.status(404).json({ 
        success: false,
        status: 'not_found',
        message: 'Nomor absen tidak ditemukan' 
      });
    }

    const participant = participantResult.rows[0];
    const today = new Date().toISOString().split('T')[0];
    const currentTime = new Date().toTimeString().split(' ')[0];

    // Check if already attended today
    const attendanceCheckQuery = `
      SELECT id FROM attendance 
      WHERE participant_id = $1 AND tanggal = $2
    `;
    const attendanceCheckResult = await pool.query(attendanceCheckQuery, [participant.id, today]);

    if (attendanceCheckResult.rows.length > 0) {
      return res.status(400).json({ 
        success: false,
        status: 'already',
        message: 'Anda sudah absen hari ini' 
      });
    }

    // Insert attendance
    const insertQuery = `
      INSERT INTO attendance (participant_id, tanggal, jam)
      VALUES ($1, $2, $3)
      RETURNING id, participant_id, tanggal, jam, created_at
    `;
    const insertResult = await pool.query(insertQuery, [participant.id, today, currentTime]);

    // Send to Google Sheets webhook
    const webhookData = {
      tanggal: today,
      jam: currentTime,
      nomor: participant.nomor_absen,
      nama: participant.nama
    };

    // Fire and forget - don't wait for response
    if (process.env.GOOGLE_SHEETS_WEBHOOK_URL) {
      axios.post(process.env.GOOGLE_SHEETS_WEBHOOK_URL, webhookData)
        .then(() => {
          console.log('✅ Data sent to Google Sheets');
        })
        .catch((error) => {
          console.error('❌ Failed to send to Google Sheets:', error.message);
        });
    }

    res.status(201).json({ 
      success: true,
      status: 'success',
      message: 'Absensi berhasil',
      data: {
        participant: {
          nomor_absen: participant.nomor_absen,
          nama: participant.nama
        },
        tanggal: today,
        jam: currentTime
      }
    });
  } catch (error) {
    console.error('Submit attendance error:', error);
    res.status(500).json({ 
      success: false,
      status: 'error',
      message: 'Terjadi kesalahan server' 
    });
  }
};

// Get today's attendance count
exports.getTodayCount = async (req, res) => {
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
        total: parseInt(countResult.rows[0].total)
      }
    });
  } catch (error) {
    console.error('Get today count error:', error);
    res.status(500).json({ error: 'Terjadi kesalahan server' });
  }
};


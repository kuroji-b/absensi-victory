<?php
/**
 * Absen.php - Attendance Submission Handler
 * Accepts POST requests with nomor_absen
 */

require_once 'config.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed');
}

// Get input
$nomor_absen = isset($_POST['nomor_absen']) ? strtoupper(sanitize($conn, $_POST['nomor_absen'])) : '';

// Validate input
if (empty($nomor_absen)) {
    jsonResponse(false, 'Nomor absen wajib diisi');
}

// Get current date and time
$tanggal = date('Y-m-d');
$jam = date('H:i:s');

// Check if participant exists
$stmt = $conn->prepare("SELECT id, nama FROM peserta WHERE nomor_absen = ?");
$stmt->bind_param("s", $nomor_absen);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    jsonResponse(false, 'Nomor absen tidak ditemukan', ['status' => 'not_found']);
}

$peserta = $result->fetch_assoc();
$stmt->close();

// Check if already attended today
$stmt = $conn->prepare("SELECT id FROM absensi WHERE nomor_absen = ? AND tanggal = ?");
$stmt->bind_param("ss", $nomor_absen, $tanggal);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    jsonResponse(false, 'Anda sudah absen hari ini', ['status' => 'already']);
}
$stmt->close();

// Insert attendance
$stmt = $conn->prepare("INSERT INTO absensi (nomor_absen, tanggal, jam) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nomor_absen, $tanggal, $jam);

if ($stmt->execute()) {
    jsonResponse(true, 'Absensi berhasil', [
        'status' => 'success',
        'nomor_absen' => $nomor_absen,
        'nama' => $peserta['nama'],
        'tanggal' => $tanggal,
        'jam' => $jam
    ]);
} else {
    jsonResponse(false, 'Gagal menyimpan absensi', ['status' => 'error']);
}

$stmt->close();
$conn->close();


<?php
/**
 * Get Count - Returns today's attendance count
 */

require_once 'config.php';

header('Content-Type: application/json');

$tanggal = date('Y-m-d');

// Get attendance count today
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM absensi WHERE tanggal = ?");
$stmt->bind_param("s", $tanggal);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$hadir = $row['total'];
$stmt->close();

// Get total participants
$result = $conn->query("SELECT COUNT(*) as total FROM peserta");
$row = $result->fetch_assoc();
$total = $row['total'];

echo json_encode([
    'success' => true,
    'data' => [
        'hadir' => $hadir,
        'total' => $total,
        'tanggal' => $tanggal
    ]
]);

$conn->close();


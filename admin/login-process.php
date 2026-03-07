<?php
/**
 * Login Process Handler
 */

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = sanitize($conn, $_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    $_SESSION['error'] = 'Username dan password wajib diisi';
    header('Location: login.php');
    exit;
}

// Check admin credentials
$stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = 'Username atau password salah';
    header('Location: login.php');
    exit;
}

$admin = $result->fetch_assoc();
$stmt->close();

// Verify password
if (!password_verify($password, $admin['password'])) {
    $_SESSION['error'] = 'Username atau password salah';
    header('Location: login.php');
    exit;
}

// Set session
$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_user'] = $username;

// Redirect to dashboard
header('Location: dashboard.php');
exit;


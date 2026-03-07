-- Database Schema for Absensi Online
-- PHP + MySQL Version

-- Create database
CREATE DATABASE IF NOT EXISTS absensi;
USE absensi;

-- Table: peserta (participants)
CREATE TABLE IF NOT EXISTS peserta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_absen VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: absensi (attendance)
CREATE TABLE IF NOT EXISTS absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_absen VARCHAR(20) NOT NULL,
    tanggal DATE NOT NULL,
    jam TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_attendance (nomor_absen, tanggal)
);

-- Table: admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (password: admin123)
-- Using bcrypt hash
INSERT INTO admin (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE username = username;

-- Sample data (optional - for testing)
-- INSERT INTO peserta (nomor_absen, nama) VALUES 
-- ('ABS001', 'John Doe'),
-- ('ABS002', 'Jane Smith'),
-- ('ABS003', 'Bob Wilson');



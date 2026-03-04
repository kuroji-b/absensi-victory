-- =====================================================
-- ABSENSI ONLINE DATABASE SCHEMA FOR SUPABASE (POSTGRESQL)
-- =====================================================

-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- =====================================================
-- TABLE: participants
-- =====================================================
CREATE TABLE IF NOT EXISTS participants (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    nomor_absen VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(255) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Create index for faster lookups
CREATE INDEX IF NOT EXISTS idx_participants_nomor_absen ON participants(nomor_absen);

-- =====================================================
-- TABLE: attendance
-- =====================================================
CREATE TABLE IF NOT EXISTS attendance (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    participant_id UUID NOT NULL REFERENCES participants(id) ON DELETE CASCADE,
    tanggal DATE NOT NULL,
    jam TIME NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraint: 1 participant only 1 attendance per date
    UNIQUE(participant_id, tanggal)
);

-- Create indexes for faster queries
CREATE INDEX IF NOT EXISTS idx_attendance_tanggal ON attendance(tanggal);
CREATE INDEX IF NOT EXISTS idx_attendance_participant_id ON attendance(participant_id);

-- =====================================================
-- ADD SAMPLE DATA (OPTIONAL - FOR TESTING)
-- =====================================================
-- Uncomment below to add sample participants

-- INSERT INTO participants (nomor_absen, nama) VALUES 
-- ('ABS001', 'Ahmad Fauzi'),
-- ('ABS002', 'Budi Santoso'),
-- ('ABS003', 'Citra Dewi'),
-- ('ABS004', 'Dani Kurniawan'),
-- ('ABS005', 'Eka Putri');

-- =====================================================
-- ADMIN CREDENTIALS SETUP
-- =====================================================
-- Default admin credentials (stored in environment variables):
-- Username: admin
-- Password: admin123 (hashed with bcrypt)
-- 
-- To generate password hash, run in Node.js:
-- const bcrypt = require('bcryptjs');
-- const hash = bcrypt.hashSync('admin123', 10);
-- console.log(hash);
-- 
-- Default hash for 'admin123': $2a$10$YourHashHere
-- Set ADMIN_PASSWORD_HASH in .env file


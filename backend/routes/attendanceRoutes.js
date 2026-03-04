const express = require('express');
const router = express.Router();
const attendanceController = require('../controllers/attendanceController');

// Public routes (for attendance submission)
router.post('/absen', attendanceController.submitAttendance);
router.get('/today/count', attendanceController.getTodayCount);

module.exports = router;


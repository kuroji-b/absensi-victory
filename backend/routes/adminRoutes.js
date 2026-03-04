const express = require('express');
const router = express.Router();
const adminController = require('../controllers/adminController');
const authMiddleware = require('../middleware/auth');

// Public routes
router.post('/login', adminController.login);

// Protected routes
router.post('/participant', authMiddleware, adminController.addParticipant);
router.get('/participants', authMiddleware, adminController.getParticipants);
router.get('/attendance', authMiddleware, adminController.getAttendance);
router.delete('/participant/:id', authMiddleware, adminController.deleteParticipant);
router.get('/stats/today', authMiddleware, adminController.getTodayStats);

module.exports = router;


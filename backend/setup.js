// Setup Script - Generate Password Hash
// Run: node setup.js

const bcrypt = require('bcryptjs');

const defaultPassword = 'admin123';
const hash = bcrypt.hashSync(defaultPassword, 10);

console.log('\n🔐 Default Password Hash Generator');
console.log('==================================\n');
console.log(`Password: ${defaultPassword}`);
console.log(`Hash: ${hash}\n`);

console.log('📝 Add these to your .env file:');
console.log(`ADMIN_USERNAME=admin`);
console.log(`ADMIN_PASSWORD_HASH=${hash}\n`);

// Also generate a random JWT secret
const crypto = require('crypto');
const jwtSecret = crypto.randomBytes(32).toString('hex');

console.log('📝 JWT Secret (add to .env):');
console.log(`JWT_SECRET=${jwtSecret}\n`);


const fs = require('fs');

// Read the base64 file
const b64 = fs.readFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/dashboard_b64.txt', 'utf8');
console.log('Read base64, length:', b64.length);

// Decode from base64
const decoded = Buffer.from(b64, 'base64').toString('utf8');
console.log('Decoded length:', decoded.length);

// Write to dashboard.blade.php
fs.writeFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/resources/views/dashboard.blade.php', decoded, 'utf8');
console.log('dashboard.blade.php written successfully');

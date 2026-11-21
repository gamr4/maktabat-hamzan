const fs = require('fs');
const content = fs.readFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/write_base64.ps1', 'utf8');
const start = content.indexOf('@') + 2;
const end = content.indexOf('@', start);
const b64 = content.substring(start, end).trim();
console.log('b64 length:', b64.length);
const decoded = Buffer.from(b64, 'base64').toString('utf8');
fs.writeFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/resources/views/dashboard.blade.php', decoded, 'utf8');
console.log('dashboard.blade.php written successfully, length:', decoded.length);

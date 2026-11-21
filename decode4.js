const fs = require('fs');
const content = fs.readFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/write_base64.ps1', 'utf8');
const cleanContent = content.replace(/^\uFEFF/, '');
// The format is:  = @'
// [base64 content]
const startMarker = String.fromCharCode(64, 39);
const start = cleanContent.indexOf(startMarker);
if (start === -1) {
    console.log('Start marker not found');
    process.exit(1);
}
const firstNewline = cleanContent.indexOf('\n', start);
const b64 = cleanContent.substring(firstNewline + 1).trim();
console.log('b64 length:', b64.length);
console.log('First 50 chars of b64:', b64.substring(0, 50));
const decoded = Buffer.from(b64, 'base64').toString('utf8');
fs.writeFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/resources/views/dashboard.blade.php', decoded, 'utf8');
console.log('dashboard.blade.php written successfully, length:', decoded.length);

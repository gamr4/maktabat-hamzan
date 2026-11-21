const fs = require('fs');

// Read the template from a raw text file
const template = fs.readFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/template_raw.txt', 'utf8');

// Write to dashboard.blade.php
fs.writeFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/resources/views/dashboard.blade.php', template, 'utf8');
console.log('dashboard.blade.php written successfully, length:', template.length);

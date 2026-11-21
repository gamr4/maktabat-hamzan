const fs = require('fs');

// Read all section files
const section1 = fs.readFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/dashboard_section1.txt', 'utf8');
const section2 = fs.readFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/section2.txt', 'utf8');
const section3 = fs.readFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/section3.txt', 'utf8');
const section4 = fs.readFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/section4.txt', 'utf8');
const section5 = fs.readFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/section5.txt', 'utf8');

// Decode base64 sections
const s2 = Buffer.from(section2, 'base64').toString('utf8');
const s3 = Buffer.from(section3, 'base64').toString('utf8');
const s4 = Buffer.from(section4, 'base64').toString('utf8');
const s5 = Buffer.from(section5, 'base64').toString('utf8');

// Build complete dashboard
const fullContent = "@extends('layouts.app')\n\n@section('eyebrow', 'Console')\n@section('title', 'Tableau de bord')\n\n@section('content')\n" + section1 + s2 + s3 + s4 + s5 + "\n@endsection\n";

fs.writeFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/resources/views/dashboard.blade.php', fullContent, 'utf8');
console.log('dashboard.blade.php written successfully, length:', fullContent.length);

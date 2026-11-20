<?php
require 'vendor/autoload.php';
\ = require 'bootstrap/app.php';
\->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

\ = \Illuminate\Support\Facades\DB::connection()->getPdo();

// Create users table
\->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    email_verified_at TEXT,
    password TEXT NOT NULL,
    remember_token TEXT,
    created_at TEXT,
    updated_at TEXT
)');

// Create password_reset_tokens table
\->exec('CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email TEXT PRIMARY KEY,
    token TEXT NOT NULL,
    created_at TEXT
)');

// Create sessions table
\->exec('CREATE TABLE IF NOT EXISTS sessions (
    id TEXT PRIMARY KEY,
    user_id INTEGER,
    ip_address TEXT,
    user_agent TEXT,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
)');

// Create migrations table
\->exec('CREATE TABLE IF NOT EXISTS migrations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    migration TEXT NOT NULL,
    batch INTEGER NOT NULL
)');

echo 'Tables created successfully!' . PHP_EOL;


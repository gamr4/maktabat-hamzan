<?php

require __DIR__ . '/vendor/autoload.php';

\ = require_once __DIR__ . '/bootstrap/app.php';
\->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "Running migrations...\n";
\ = Artisan::call('migrate', ['--force' => true]);
echo Artisan::output();
echo "Exit code: \\n";

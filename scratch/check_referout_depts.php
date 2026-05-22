<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $depts = DB::connection('hosxp')->table('referout')->distinct()->pluck('department');
    print_r($depts);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

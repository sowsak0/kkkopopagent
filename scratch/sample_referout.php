<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $sample = DB::connection('hosxp')->table('referout')->limit(5)->get();
    print_r($sample);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

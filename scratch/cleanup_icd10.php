<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Cleaning up lookup_icd10 records...\n";

try {
    $deleted = DB::table('lookup_icd10')
        ->where(function($query) {
            $query->whereNull('pp')->orWhere('pp', '!=', 'Y');
        })
        ->where(function($query) {
            $query->whereNull('ods')->orWhere('ods', '!=', 'Y');
        })
        ->delete();

    echo "Deleted $deleted records that were neither PP nor ODS.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

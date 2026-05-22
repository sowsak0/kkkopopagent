<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Dropping columns...\n";

try {
    Schema::table('lookup_icd10', function (Blueprint $table) {
        $table->dropColumn(['ods_p', 'kidney', 'hiv', 'tb']);
    });
    echo "Columns dropped successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

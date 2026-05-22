<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lookup_icd10', function (Blueprint $table) {
            $table->string('icd10', 100)->primary();
            $table->string('pp', 1)->nullable();
            $table->string('ods', 1)->nullable();
            $table->timestamps();
        });

        Schema::create('lookup_hospcode', function (Blueprint $table) {
            $table->string('hospcode', 9)->primary();
            $table->string('hospcode_name', 100)->nullable();
            $table->string('hmain_ucs', 1)->nullable();
            $table->string('hmain_sss', 1)->nullable();
            $table->string('in_province', 1)->nullable();
            $table->timestamps();
        });

        Schema::create('lookup_ward', function (Blueprint $table) {
            $table->string('ward', 2)->primary();
            $table->string('ward_name', 100)->nullable();
            $table->string('ward_normal', 1)->nullable();
            $table->string('ward_m', 1)->nullable();
            $table->string('ward_f', 1)->nullable();
            $table->string('ward_vip', 1)->nullable();
            $table->string('ward_lr', 1)->nullable();
            $table->string('ward_homeward', 1)->nullable();
            $table->integer('bed_qty')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lookup_ward');
        Schema::dropIfExists('lookup_hospcode');
        Schema::dropIfExists('lookup_icd10');
    }
};

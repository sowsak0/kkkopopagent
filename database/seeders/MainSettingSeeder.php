<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('main_setting')->insert([
            ['name' => 'opoh_token', 'value' => 'YOUR_TOKEN_HERE'],
            ['name' => 'opoh_url', 'value' => 'https://api.example.com'],
            ['name' => 'bed_qty', 'value' => '100'],
        ]);
    }
}

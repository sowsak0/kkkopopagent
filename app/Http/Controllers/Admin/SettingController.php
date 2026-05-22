<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class SettingController extends Controller
{
    public function upgradeStructure()
    {
        // 1. ตาราง main_setting
        if (!Schema::hasTable('main_setting')) {
            Schema::create('main_setting', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // 2. ตาราง lookup_icd10
        if (!Schema::hasTable('lookup_icd10')) {
            Schema::create('lookup_icd10', function (Blueprint $table) {
                $table->string('icd10', 100)->primary();
                $table->string('pp', 1)->nullable();
                $table->string('ods', 1)->nullable();
                $table->timestamps();
            });
        }

        // 3. ตาราง lookup_hospcode
        if (!Schema::hasTable('lookup_hospcode')) {
            Schema::create('lookup_hospcode', function (Blueprint $table) {
                $table->string('hospcode', 9)->primary();
                $table->string('hospcode_name', 100)->nullable();
                $table->string('hmain_ucs', 1)->nullable();
                $table->string('hmain_sss', 1)->nullable();
                $table->string('in_province', 1)->nullable();
                $table->timestamps();
            });
        }

        // 4. ตาราง lookup_ward
        if (!Schema::hasTable('lookup_ward')) {
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

        return back()->with('success', 'Database structure upgraded successfully.');
    }

    public function index()
    {
        $settings = DB::table('main_setting')->get();
        return view('admin.settings.index', compact('settings'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'nullable',
        ]);

        DB::table('main_setting')->where('id', $id)->update([
            'value' => $request->value,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Setting updated successfully.');
    }


}

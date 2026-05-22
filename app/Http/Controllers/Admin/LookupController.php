<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LookupWard;

class LookupController extends Controller
{
    public function index()
    {
        return view('admin.lookups.index');
    }

    public function show($type)
    {
        $validTypes = ['icd10', 'hospcode', 'ward'];
        if (!in_array($type, $validTypes)) abort(404);

        $table = "lookup_$type";
        $primaryKey = $type === 'ward' ? 'ward' : ($type === 'icd10' ? 'icd10' : 'hospcode');
        $data = DB::table($table)->orderBy($primaryKey, 'asc')->get();
        
        return view("admin.lookups.show", [
            'type' => $type,
            'data' => $data,
            'title' => strtoupper($type) . ' Lookup'
        ]);
    }

    public function store(Request $request, $type)
    {
        $table = "lookup_$type";
        $data = $request->except(['_token']);

        // Handle Y/N toggles
        if ($type === 'icd10') {
            $data['pp'] = $request->has('pp') ? 'Y' : 'N';
            $data['ods'] = $request->has('ods') ? 'Y' : 'N';
        } elseif ($type === 'hospcode') {
            $data['hmain_ucs'] = $request->has('hmain_ucs') ? 'Y' : 'N';
            $data['hmain_sss'] = $request->has('hmain_sss') ? 'Y' : 'N';
            $data['in_province'] = $request->has('in_province') ? 'Y' : 'N';
        }

        DB::table($table)->insert($data);

        return back()->with('success', 'Record added successfully.');
    }

    public function update(Request $request, $type, $id)
    {
        $table = "lookup_$type";
        $primaryKey = $type === 'ward' ? 'ward' : ($type === 'icd10' ? 'icd10' : 'hospcode');
        $data = $request->except(['_token', '_method']);
        
        // Handle Y/N toggles
        if ($type === 'icd10') {
            $data['pp'] = $request->has('pp') ? 'Y' : 'N';
            $data['ods'] = $request->has('ods') ? 'Y' : 'N';
        } elseif ($type === 'hospcode') {
            $data['hmain_ucs'] = $request->has('hmain_ucs') ? 'Y' : 'N';
            $data['hmain_sss'] = $request->has('hmain_sss') ? 'Y' : 'N';
            $data['in_province'] = $request->has('in_province') ? 'Y' : 'N';
        }

        DB::table($table)->where($primaryKey, $id)->update($data);

        return back()->with('success', 'Record updated successfully.');
    }

    public function destroy($type, $id)
    {
        $table = "lookup_$type";
        $primaryKey = $type === 'ward' ? 'ward' : ($type === 'icd10' ? 'icd10' : 'hospcode');
        
        DB::table($table)->where($primaryKey, $id)->delete();

        return back()->with('success', 'Record deleted successfully.');
    }

    public function importWard()
    {
        $hosxp_data = DB::connection('hosxp')->select('
            SELECT ward, `name` AS ward_name FROM ward WHERE ward_active = "Y"'
        );

        $inserted = 0;
        $updated  = 0;

        foreach ($hosxp_data as $row) {
            $exists = DB::table('lookup_ward')->where('ward', $row->ward)->exists();

            if ($exists) {
                DB::table('lookup_ward')
                    ->where('ward', $row->ward)
                    ->update(['ward_name' => $row->ward_name]);
                $updated++;
            } else {
                DB::table('lookup_ward')->insert([
                    'ward'      => $row->ward,
                    'ward_name' => $row->ward_name,
                ]);
                $inserted++;
            }
        }

        return back()->with('success', "นำเข้าข้อมูล Ward สำเร็จ: เพิ่มใหม่ {$inserted} รายการ, อัปเดต {$updated} รายการ");
    }

    public function toggleWardColumn(Request $request)
    {
        $allowed = ['ward_normal', 'ward_m', 'ward_f', 'ward_vip', 'ward_lr', 'ward_homeward'];
        $col  = $request->input('col');
        $ward = $request->input('ward');

        if (!in_array($col, $allowed)) {
            return response()->json(['ok' => false, 'message' => 'Invalid column'], 422);
        }

        $current = DB::table('lookup_ward')->where('ward', $ward)->value($col);
        $new     = ($current === 'Y') ? 'N' : 'Y';

        DB::table('lookup_ward')->where('ward', $ward)->update([$col => $new]);

        return response()->json(['ok' => true, 'value' => $new]);
    }
}

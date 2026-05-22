<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'lookup_icd10' => DB::table('lookup_icd10')->count(),
            'lookup_hospcode' => DB::table('lookup_hospcode')->count(),
            'lookup_ward' => DB::table('lookup_ward')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

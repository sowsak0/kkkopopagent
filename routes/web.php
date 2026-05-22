<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LookupController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('settings.index');
    });
    
    // Users CRUD
    Route::resource('users', UserController::class);
    
    // Settings CRUD
    Route::post('settings/upgrade', [\App\Http\Controllers\Admin\SettingController::class, 'upgradeStructure'])->name('settings.upgrade');
    Route::resource('settings', \App\Http\Controllers\Admin\SettingController::class)->only(['index', 'update']);
    
    // Lookups CRUD
    Route::get('lookups', [LookupController::class, 'index'])->name('admin.lookups.index');
    Route::get('lookups/{type}', [LookupController::class, 'show'])->name('admin.lookups.show');
    Route::post('lookups/ward/import', [LookupController::class, 'importWard'])->name('admin.lookups.ward.import');
    Route::post('lookups/ward/toggle', [LookupController::class, 'toggleWardColumn'])->name('admin.lookups.ward.toggle');
    Route::post('lookups/{type}', [LookupController::class, 'store'])->name('admin.lookups.store');
    Route::put('lookups/{type}/{id}', [LookupController::class, 'update'])->name('admin.lookups.update');
    Route::delete('lookups/{type}/{id}', [LookupController::class, 'destroy'])->name('admin.lookups.destroy');
});

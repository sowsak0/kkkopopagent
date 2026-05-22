<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OpodSendController;

Route::match(['get', 'post'], '/opod-send', [OpodSendController::class, 'send']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

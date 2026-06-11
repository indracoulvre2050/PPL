<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post('/kirim-sensor', [ApiController::class, 'terimaDataSensor']);
Route::get('/data-terbaru', [ApiController::class, 'ambilDataTerbaru']);
Route::get('/ambil-batas', [ApiController::class, 'kirimBatasKeAlat']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
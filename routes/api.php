<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RiwayatStokController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    // Public endpoints
    Route::post('/login', [AuthController::class, 'login']);

    // Protected endpoints
    Route::get('/barang', [BarangController::class, 'index']);
    Route::get('/barang/stats', [BarangController::class, 'stats']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::get('/barang/{id}', [BarangController::class, 'show']);
    Route::put('/barang/{id}', [BarangController::class, 'update']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

    Route::get('/riwayat', [RiwayatStokController::class, 'index']);
    Route::post('/riwayat', [RiwayatStokController::class, 'store']);
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiBukuController;

Route::get('/halo', function () {
    return response()->json([
        'pesan' => 'Halo dari Laravel API!',
        'versi' => '1.0'
    ]);
});

// ✅ Gunakan prefix agar tidak bentrok dengan route web
Route::name('api.buku.')->group(function () {
    Route::apiResource('buku', ApiBukuController::class);
});

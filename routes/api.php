<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\SupplierController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('karyawan')
    ->name('api.karyawan.')
    ->group(function () {
        Route::get('/register', [KaryawanController::class, 'register'])->name('register');
        Route::get('/ref', [KaryawanController::class, 'list'])->name('ref');
        Route::get('reg/dokter', [KaryawanController::class, 'registerDokter'])->name('reg.dokter');
    });


# Wilayah
Route::get('prov', [WilayahController::class, 'prov'])->name('api.prov');
Route::get('kab/{id?}', [WilayahController::class, 'kab'])->name('api.kab');
Route::get('kec/{id?}', [WilayahController::class, 'kec'])->name('api.kec');
Route::get('desa/{id?}', [WilayahController::class, 'desa'])->name('api.desa');


// Master Data
Route::get('ruangan', [RuanganController::class, 'list'])->name('api.ruangan');
Route::get('supplier', [SupplierController::class, 'list'])->name('api.supplier');

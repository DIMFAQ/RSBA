<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('karyawan')
    ->name('api.karyawan.')
    ->group(function () {
        Route::get('/register', 'KaryawanController@register')->name('register');
    });

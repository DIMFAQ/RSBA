<?php

use Illuminate\Support\Facades\Route;

Route::prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {

        Route::get('/', App\Livewire\Karyawan\Index::class)->name('index');
        Route::get('/edit/{id}', App\Livewire\Karyawan\Edit::class)->name('edit');
    });


Route::prefix('master')
    ->name('master.')
    ->group(function () {


        Route::prefix('jabatan')
            ->name('jabatan.')
            ->group(function () {
                Route::get('/', App\Livewire\Master\Jabatan\Index::class)->name('index');
            });

        Route::prefix('ruangan')
            ->name('ruangan.')
            ->group(function () {
                Route::get('/', App\Livewire\Master\Ruangan\Index::class)->name('index');
            });

        Route::prefix('bagian')
            ->name('bagian.')
            ->group(function () {
                Route::get('/', App\Livewire\Master\Bagian\Index::class)->name('index');
            });

        Route::prefix('spesialisasi')
            ->name('spesialisasi.')
            ->group(function () {

                Route::get('/', App\Livewire\Master\Spesialisasi\Index::class)->name('index');
            });
    });

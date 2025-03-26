<?php

use Illuminate\Support\Facades\Route;

// Karyawan
Route::prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {

        Route::get('/', App\Livewire\Karyawan\Index::class)->name('index');
        Route::get('/edit/{id}', App\Livewire\Karyawan\Edit::class)->name('edit');
    });


// Master data
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


// Surat Surat
Route::prefix('surat')
    ->name('surat.')
    ->group(function () {
        Route::get('cuti', App\Livewire\Surat\Cuti\Index::class)->name('cuti');
        Route::get('cuti/approval/{id?}', App\Livewire\Surat\Cuti\Approval::class)->name('cuti.approval');

        Route::get('sp3', App\Livewire\Surat\Sp3\Index::class)->name('sp3');
    });


// Jasa Medis
Route::prefix('jasmed')
    ->name('jasmed.')
    ->group(function () {
        Route::get('/', App\Livewire\Jasmed\Index::class)->name('index');
    });

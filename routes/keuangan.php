<?php

use Illuminate\Support\Facades\Route;


Route::prefix('hutang')
    ->name('hutang.')
    ->group(function () {
        Route::get('/', App\Livewire\Hutang\Index::class)->name('index');
    });

Route::prefix('piutang')
    ->name('piutang.')
    ->group(function () {
        Route::get('/', App\Livewire\Piutang\Index::class)->name('index');
    });

Route::prefix('laporan')
    ->name('laporan.')
    ->group(function () {
        Route::get('/', App\Livewire\Laporan\Keuangan\Index::class)->name('index');
    });

<?php

use Illuminate\Support\Facades\Route;

Route::prefix('master')
    ->name('master.')
    ->group(function () {
        Route::get('barang', App\Livewire\Master\Barang\Index::class)->name('barang');
        Route::get('kategori', App\Livewire\Master\Barang\Kategori\Index::class)->name('kategori');
        Route::get('satuan', App\Livewire\Master\Barang\Satuan\Index::class)->name('satuan');
        Route::get('supplier', App\Livewire\Master\Supplier\Index::class)->name('supplier');
        Route::get('penyimpanan', App\Livewire\Master\Penyimpanan\Index::class)->name('penyimpanan');
    });

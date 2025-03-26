<?php

use Illuminate\Support\Facades\Route;

Route::prefix('master')
    ->name('master.')
    ->group(function () {

        Route::get('supplier', App\Livewire\Master\Supplier\Index::class)->name('supplier');
    });

<?php

use Illuminate\Support\Facades\Route;

// Administrator
// Route::middleware('auth')
//     ->prefix('admin')
//     ->name('admin.')
//     ->group(function () {

Route::prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/', App\Livewire\User\Index::class)->name('index');
    });

Route::prefix('settings')
    ->name('settings.')
    ->group(function () {
        Route::get('/menu', App\Livewire\Settings\Menu\Index::class)->name('menu');
        Route::get('/perusahaan', App\Livewire\Settings\Perusahaan\Index::class)->name('perusahaan');
        Route::get('/role', App\Livewire\Settings\Role\Index::class)->name('role');
        Route::get('/permission', App\Livewire\Settings\Permission\Index::class)->name('permission');
    });
    // });

<?php

namespace App\Providers;

use App\Models\Perusahaan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class CompanyIdServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind a single instance of `rs` so it doesn't run multiple times
        $this->app->singleton('rs', function () {
            return Cache::remember('rs_data', now()->addMinutes(180), function () {
                return Schema::hasTable('perusahaan') ? Perusahaan::first() : null;
            });
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('rs', app('rs'));
        });
    }
}

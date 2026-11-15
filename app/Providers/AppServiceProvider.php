<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\ReservedShortCodes;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(ReservedShortCodes::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFour();

        View::creator(['auth.*', 'dashboard.*', 'admin.*'], function ($view) {
            $view->with('settings', Setting::first());
        });
    }
}

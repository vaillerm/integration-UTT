<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Config;
use Blade;

class AssetVersionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('asset', function ($expression) {
            return 'asset'.$expression.'.\'?'.Config::get('services.version.hash').'\'';
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

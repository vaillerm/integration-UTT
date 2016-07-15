<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Config;
use Blade;

class CountdownServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('countdown', function ($expression) {
            return '(('.$expression.'->invert)?\'-\':\'\').'.$expression.'->days'.'.\'j \'.'.$expression.'->h'.'.\'h \'.'.$expression.'->i'.'.\'min \'';
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

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $server = $_SERVER;
        $cdn1 = 'HTTP_X_FORWARDED_PROTO';
        $cdn2 = 'HTTP_X_FORWARDED_FOR';
        if (array_key_exists($cdn1, $server) || array_key_exists($cdn2, $server) || request()->secure()){
            URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     //
    // }
    public function boot()
    {
        // Force HTTPS for all URLs when using ngrok or in production
        if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) || 
           $this->app->environment('production') ||
           str_contains(request()->getHost(), 'ngrok')) {
            URL::forceScheme('https');
        }
    }
}

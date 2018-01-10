<?php

namespace App\Providers;

use App\Browsers\Chrome;
use App\Browsers\Phantom;
use App\Browsers\Safari;
use App\Contracts\Browser;
use Illuminate\Support\ServiceProvider;

class BrowserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->bind(Browser::class, Phantom::class);
//        $this->app->bind(Browser::class, Chrome::class);
        $this->app->bind(Browser::class, Safari::class);
    }
}
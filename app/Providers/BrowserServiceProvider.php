<?php

namespace App\Providers;

use App\Browsers\Phantom;
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
        $this->app->bind(Browser::class, Phantom::class);
    }
}

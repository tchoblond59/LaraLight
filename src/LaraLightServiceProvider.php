<?php

namespace Tchoblond59\LaraLight;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class LaraLightServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'laralight');

        Event::listen('App\Events\MSMessageEvent', '\Tchoblond59\LaraLight\EventListener\LaraLightEventListener');
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

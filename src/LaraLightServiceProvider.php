<?php

namespace Tchoblond59\LaraLight;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Tchoblond59\LaraLight\Console\Commands\AutoSwitchOffCommand;

class LaraLightServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/assets/js' => public_path('/js/tchoblond59/laralight'),
            __DIR__.'/assets/css' => public_path('/css/tchoblond59/laralight'),
        ], 'larahome-package');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'laralight');
        if ($this->app->runningInConsole()) {
            $this->commands([
                AutoSwitchOffCommand::class,
            ]);
        }

        Event::listen('App\Events\MSMessageEvent', '\Tchoblond59\LaraLight\EventListener\LaraLightEventListener');

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('laralight:auto_switch_off')->everyMinute();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /*$this->app->singleton('tchoblond59.laralight.console.kernel', function($app) {
            $dispatcher = $app->make(\Illuminate\Contracts\Events\Dispatcher::class);
            return new \Tchoblond59\LaraLight\Console\Kernel($app, $dispatcher);
        });

        $this->app->make('tchoblond59.laralight.console.kernel');*/
    }
}

<?php

namespace Star\Laravel\Robot;


use Illuminate\Support\ServiceProvider;

class RobotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/robots.php' => config_path('robots.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('robot', function ($app) {
            return new RobotManager($app);
        });
    }

}

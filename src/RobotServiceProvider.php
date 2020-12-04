<?php

namespace Starme\Robot;


use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Pipeline\Pipeline;
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
        ], 'robot-config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ChatRobot::class, function($app) {
            return new ChatRobot(
                $app->make(Dispatcher::class),
                $app->make(Pipeline::class),
                $app['config']['robots']
            );
        });

        $this->app->singleton('robot.chat', function ($app) {
            return new RobotManager($app);
        });
    }

}

<?php

namespace Kim\Activity;

use Illuminate\Support\ServiceProvider;

use Kim\Activity\Session\DatabaseWithUserSessionHandler;

class ActivityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../migrations/' => base_path('/database/migrations')
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->extendSessionHandler();

        $this->app->bind('Activity', function($app)
        {
            return new Activity;
        });
    }

    /**
     * Extend the database session handler to include the user_id.
     *
     * @return void
     */
    private function extendSessionHandler()
    {
        $connection = $this->app['config']['session.connection'];
        $table = $this->app['config']['session.table'];
        $lifetime = $this->app['config']['session.lifetime'];

        $this->app['session']->extend('database', function($app) use ($connection, $table, $lifetime)
        {
            return new DatabaseWithUserSessionHandler($this->app['db']->connection($connection), $table, $lifetime);
        });
    }
}

<?php

namespace Kim\Activity;

use Illuminate\Support\ServiceProvider;

use Kim\Activity\Session\DatabaseWithUserSessionHandler;

class ActivityServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Activity', function($app)
        {
            return new Activity;
        });
    }
}

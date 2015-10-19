<?php

namespace Kim\Activity;

use Illuminate\Support\Facades\Facade;

class ActivityFacade extends Facade
{

    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Activity';
    }
}
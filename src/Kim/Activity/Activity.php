<?php

namespace Kim\Activity;

use Kim\Activity\Traits\ActivitySupporter;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use ActivitySupporter;

    /**
     * The activity model uses the 'sessions' database.
     *
     * @var string
     */
    protected $table = 'sessions';

    /**
     * There are no timestamps.
     *
     * @var bool
     */
    public $timestamps = false;
}

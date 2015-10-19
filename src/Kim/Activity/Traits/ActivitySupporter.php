<?php

namespace Kim\Activity\Traits;

use Kim\Activity\Scopes\ActivityOrderingScope;
use Kim\Activity\Scopes\GuestRetrievingScope;
use Kim\Activity\Scopes\UserRetrievingScope;

trait ActivitySupporter
{

    /**
     * BelongsTo relationship with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.model'));
    }

    /**
     * Boot the scopes on this activity trait for a model.
     *
     * @return void
     */
    public static function bootActivitySupporter()
    {
        static::addGlobalScope(new ActivityOrderingScope);
        static::addGlobalScope(new GuestRetrievingScope);
        static::addGlobalScope(new UserRetrievingScope);
    }

}
<?php

namespace Kim\Activity\Traits;

trait ActivitySupporter
{
    use ActivitySorter, GuestRetriever, UserRetriever;

    /**
     * BelongsTo relationship with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}

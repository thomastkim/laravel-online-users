<?php

namespace Kim\Activity\Traits;

trait GuestRetriever
{
    /**
     * Constrain the query to retrieve only sessions of guests who
     * have been active within the specified number of seconds.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $seconds
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeGuestsBySeconds($query, $seconds = 60)
    {
        return  $query->where('last_activity', '>=', time() - $seconds)->whereNull('user_id');
    }

    /**
     * Alias for the `guestsByMinutes` query method.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $minutes
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeGuests($query, $minutes = 5)
    {
        return $query->guestsByMinutes($minutes);
    }

    /**
     * Constrain the query to retrieve only sessions of guests who
     * have been active within the specified number of minutes.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $minutes
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeGuestsByMinutes($query, $minutes = 5)
    {
        return $query->guestsBySeconds($minutes * 60);
    }

    /**
     * Constrain the query to retrieve only sessions of guests who
     * have been active within the specified number of hours.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $hours
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeGuestsByHours($query, $hours = 1)
    {
        return $query->guestsByMinutes($hours * 60);
    }
}
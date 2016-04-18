<?php

namespace Kim\Activity\Traits;

trait UserRetriever
{
    /**
     * Constrain the query to retrieve only sessions of users who
     * have been active within the specified number of seconds.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $seconds
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeUsersBySeconds($query, $seconds = 60)
    {
        return  $query->with(['user'])->where('last_activity', '>=', time() - $seconds)->whereNotNull('user_id');
    }

    /**
     * Alias for the `usersByMinutes` query method.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $minutes
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeUsers($query, $minutes = 5)
    {
        return $query->usersByMinutes($minutes);
    }

    /**
     * Constrain the query to retrieve only sessions of users who
     * have been active within the specified number of minutes.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $minutes
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeUsersByMinutes($query, $minutes = 5)
    {
        return $query->usersBySeconds($minutes * 60);
    }

    /**
     * Constrain the query to retrieve only sessions of users who
     * have been active within the specified number of hours.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $hours
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeUsersByHours($query, $hours = 1)
    {
        return $query->usersByMinutes($hours * 60);
    }
}
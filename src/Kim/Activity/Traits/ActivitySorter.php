<?php

namespace Kim\Activity\Traits;

trait ActivitySorter
{
    /**
     * Add an "order by" clause to retrieve most recent sessions.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $column
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeMostRecent($query, $column = 'last_activity')
    {
        return $query->latest($column);
    }

    /**
     * Add an "order by" clause to retrieve least recent sessions.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $column
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeLeastRecent($query, $column = 'last_activity')
    {
        return $query->oldest($column);
    }

    /**
     * Use joins to order by the users' column attributes.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $column
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeOrderByUsers($query, $column, $dir = 'ASC')
    {
        $table = $this->getTable();

        $userModel = config('auth.providers.users.model');
        $user = new $userModel;
        $userTable = $user->getTable();
        $userKey = $user->getKeyName();

        return $query->join($userTable, "{$table}.user_id", '=', "{$userTable}.{$userKey}")->orderBy("{$userTable}.{$column}", $dir);
    }
}
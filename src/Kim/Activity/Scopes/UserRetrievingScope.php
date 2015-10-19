<?php

namespace Kim\Activity\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class UserRetrievingScope implements ScopeInterface
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $query, Model $model)
    {
        $this->registerMacro($query, $model);

        $this->registerHelpers($query);
    }

    /**
     * Register a macro that retrieves all online users
     * by using $seconds as the parameter.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Model   $model
     * @return void
     */
    protected function registerMacro(Builder $query, Model $model)
    {
        $macro = function (Builder $query, $seconds = 60) use ($model)
        {
            $lastActivity = ($model->lastActivity) ?: 'last_activity';

            return  $query->with('user')->where($lastActivity, '>=', time() - $seconds)->whereNotNull('user_id');
        };

        $query->macro('usersBySeconds', $macro);
    }

    /**
     * Register simple helper macros that allows for simpler
     * and more intuitive queries.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    protected function registerHelpers(Builder $query)
    {
        $query->macro('users', function (Builder $query, $minutes = 5)
        {
            return $query->usersByMinutes($minutes);
        });
        $query->macro('usersByMinutes', function (Builder $query, $minutes = 5)
        {
            return $query->usersBySeconds($minutes * 60);
        });
        $query->macro('usersByHours', function (Builder $query, $hours = 1)
        {
            return $query->usersByMinutes($hours * 60);
        });
    }

    /**
     * Remove the scope from given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function remove(Builder $query, Model $model) {}

}
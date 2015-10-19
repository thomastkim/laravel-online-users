<?php

namespace Kim\Activity\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class GuestRetrievingScope implements ScopeInterface
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
     * Register a macro that retrieves all online guests
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

            return  $query->where($lastActivity, '>=', time() - $seconds)->whereNull('user_id');
        };

        $query->macro('guestsBySeconds', $macro);
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
        $query->macro('guests', function (Builder $query, $minutes = 5)
        {
            return $query->guestsByMinutes($minutes);
        });
        $query->macro('guestsByMinutes', function (Builder $query, $minutes = 5)
        {
            return $query->guestsBySeconds($minutes * 60);
        });
        $query->macro('guestsByHours', function (Builder $query, $hours = 1)
        {
            return $query->guestsByMinutes($hours * 60);
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
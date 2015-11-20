<?php

namespace Kim\Activity\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class ActivityOrderingScope implements ScopeInterface
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
        $this->registerRelationshipOrderingQuery($query, $model);

        $this->registerHelpers($query);
    }

    /**
     * Register a macro that helps you sort the fetched
     * entries by a field in the related users table.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Model   $model
     * @return void
     */
    protected function registerRelationshipOrderingQuery(Builder $query, Model $model)
    {
        $macro = function (Builder $query, $column, $dir = 'ASC') use ($model)
        {
            $table = $model->getTable();

            $userTable = config('auth.table');
            $userModel = config('auth.model');
            $userKey = (new $userModel)->getKeyName();

            return $query->join($userTable, "{$table}.user_id", '=', "{$userTable}.{$userKey}")->orderBy("{$userTable}.{$column}", $dir);
        };

        $query->macro('orderByUsers', $macro);
    }

    /**
     * Register simple helper macros that sort by last_activity.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    protected function registerHelpers(Builder $query)
    {
        $query->macro('mostRecent', function (Builder $query, $column = 'last_activity')
        {
            return $query->latest($column);
        });
        $query->macro('leastRecent', function (Builder $query, $column = 'last_activity')
        {
            return $query->oldest($column);
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
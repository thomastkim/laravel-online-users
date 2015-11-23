<?php

namespace Kim\Activity\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

use DB;

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
            $table = $model->getTable();
            $prefix = DB::getTablePrefix();

            return $query->with('user')
                ->select("{$table}.*")
                ->leftJoin("{$table} as s2", function($join) use ($table, $lastActivity, $prefix) {
                    $join->on("{$table}.user_id", '=', 's2.user_id')
                        ->on(DB::raw("{$prefix}{$table}.{$lastActivity} < {$prefix}s2.{$lastActivity}"), DB::raw(''), DB::raw(''));
                })
                ->where("{$table}.{$lastActivity}", '>=', time() - $seconds)
                ->whereNotNull("{$table}.user_id")
                ->whereNull('s2.user_id');

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
<?php

use Kim\Activity\Activity;

class ActivityTest extends \Orchestra\Testbench\TestCase {

    protected $activity;

    const SECONDS_PER_MINUTE = 60;
    const MINUTES_PER_HOUR = 60;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default', 'sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');

        $this->activity = new Activity;
    }

    /** @test */
    public function it_should_get_active_users_within_the_default_timespan()
    {
        $query = $this->activity->users();

        $this->assertEquals('select "sessions".* from "sessions" left join "sessions" as "s2" on "sessions"."user_id" = "s2"."user_id" and sessions.last_activity < s2.last_activity   where "sessions"."last_activity" >= ? and "sessions"."user_id" is not null and "s2"."user_id" is null', $query->toSql());
        $this->assertEquals([time() - 300], $query->getBindings());
    }

    /** @test */
    public function it_should_get_active_users_within_a_specified_second_timespan()
    {
        $seconds = 500;

        $query = $this->activity->usersBySeconds($seconds);

        $this->assertEquals('select "sessions".* from "sessions" left join "sessions" as "s2" on "sessions"."user_id" = "s2"."user_id" and sessions.last_activity < s2.last_activity   where "sessions"."last_activity" >= ? and "sessions"."user_id" is not null and "s2"."user_id" is null', $query->toSql());
        $this->assertEquals([time() - $seconds], $query->getBindings());
    }

    /** @test */
    public function it_should_get_active_users_within_a_specified_minute_timespan()
    {
        $minutes = 10;
        $seconds = $minutes * self::SECONDS_PER_MINUTE;

        $query = $this->activity->usersByMinutes($minutes);

        $this->assertEquals('select "sessions".* from "sessions" left join "sessions" as "s2" on "sessions"."user_id" = "s2"."user_id" and sessions.last_activity < s2.last_activity   where "sessions"."last_activity" >= ? and "sessions"."user_id" is not null and "s2"."user_id" is null', $query->toSql());
        $this->assertEquals([time() - $seconds], $query->getBindings());
    }

    /** @test */
    public function it_should_get_active_users_within_a_specified_hour_timespan()
    {
        $hours = 1;
        $seconds = $hours * self::MINUTES_PER_HOUR * self::SECONDS_PER_MINUTE;

        $query = $this->activity->usersByHours($hours);

        $this->assertEquals('select "sessions".* from "sessions" left join "sessions" as "s2" on "sessions"."user_id" = "s2"."user_id" and sessions.last_activity < s2.last_activity   where "sessions"."last_activity" >= ? and "sessions"."user_id" is not null and "s2"."user_id" is null', $query->toSql());
        $this->assertEquals([time() - $seconds], $query->getBindings());
    }

    /** @test */
    public function it_should_get_active_guests_within_the_default_timespan()
    {
        $query = $this->activity->guests();

        $this->assertEquals('select * from "sessions" where "last_activity" >= ? and "user_id" is null', $query->toSql());
        $this->assertEquals([time() - 300], $query->getBindings());
    }

    /** @test */
    public function it_should_get_active_guests_within_a_specified_second_timespan()
    {
        $seconds = 500;

        $query = $this->activity->guestsBySeconds($seconds);

        $this->assertEquals('select * from "sessions" where "last_activity" >= ? and "user_id" is null', $query->toSql());
        $this->assertEquals([time() - $seconds], $query->getBindings());
    }

    /** @test */
    public function it_should_get_active_guests_within_a_specified_minute_timespan()
    {
        $minutes = 10;
        $seconds = $minutes * self::SECONDS_PER_MINUTE;

        $query = $this->activity->guestsByMinutes($minutes);

        $this->assertEquals('select * from "sessions" where "last_activity" >= ? and "user_id" is null', $query->toSql());
        $this->assertEquals([time() - $seconds], $query->getBindings());
    }

    /** @test */
    public function it_should_get_active_guests_within_a_specified_hour_timespan()
    {
        $hours = 1;
        $seconds = $hours * self::MINUTES_PER_HOUR * self::SECONDS_PER_MINUTE;

        $query = $this->activity->guestsByHours($hours);

        $this->assertEquals('select * from "sessions" where "last_activity" >= ? and "user_id" is null', $query->toSql());
        $this->assertEquals([time() - $seconds], $query->getBindings());
    }

    /** @test */
    public function it_should_sort_by_most_recent_activity()
    {
        $query = $this->activity->mostRecent();
        $this->assertEquals('select * from "sessions" order by "last_activity" desc', $query->toSql());
    }

    /** @test */
    public function it_should_sort_by_least_recent_activity()
    {
        $query = $this->activity->leastRecent();
        $this->assertEquals('select * from "sessions" order by "last_activity" asc', $query->toSql());
    }

    /** @test */
    public function it_should_sort_by_user_attributes()
    {
        $attribute1 = 'name';
        $query1 = $this->activity->orderByUsers($attribute1);
        $this->assertEquals('select * from "sessions" inner join "users" on "sessions"."user_id" = "users"."id" order by "users"."' . $attribute1 . '" asc', $query1->toSql());

        $attribute2 = 'email';
        $query2 = $this->activity->orderByUsers($attribute2);
        $this->assertEquals('select * from "sessions" inner join "users" on "sessions"."user_id" = "users"."id" order by "users"."' . $attribute2 . '" asc', $query2->toSql());
    }

    /** @test */
    public function it_should_sort_by_user_attributes_with_direction()
    {
        $attribute1 = 'name';
        $direction1 = 'asc';
        $query1 = $this->activity->orderByUsers($attribute1, $direction1);
        $this->assertEquals('select * from "sessions" inner join "users" on "sessions"."user_id" = "users"."id" order by "users"."' . $attribute1 . '" ' . $direction1, $query1->toSql());

        $attribute2 = 'email';
        $direction2 = 'desc';
        $query2 = $this->activity->orderByUsers($attribute2, $direction2);
        $this->assertEquals('select * from "sessions" inner join "users" on "sessions"."user_id" = "users"."id" order by "users"."' . $attribute2 . '" ' . $direction2, $query2->toSql());
    }

}

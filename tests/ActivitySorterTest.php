<?php

use Kim\Activity\Activity;

class ActivitySorterTest extends TestCase
{
	/** @test */
	function it_sorts_the_sessions_by_most_recent_activity()
	{
		$sessions = [];

		for ($i = 0; $i < 10; $i++)
		{
			$sessions[] = $this->createSession()->last_activity;
		}
		rsort($sessions);

		$activities = Activity::mostRecent()->get()->pluck('last_activity');

		$this->assertEquals($sessions, $activities->toArray());
	}

	/** @test */
	function it_sorts_the_sessions_by_least_recent_activity()
	{
		$sessions = [];

		for ($i = 0; $i < 10; $i++)
		{
			$sessions[] = $this->createSession()->last_activity;
		}
		sort($sessions);

		$activities = Activity::leastRecent()->get()->pluck('last_activity');

		$this->assertEquals($sessions, $activities->toArray());
	}
}
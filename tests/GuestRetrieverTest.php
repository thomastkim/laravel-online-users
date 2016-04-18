<?php

use Kim\Activity\Activity;

class GuestRetrieverTest extends TestCase
{
	/** @test */
	function it_retrieves_only_the_sessions_without_an_associated_user()
	{
		for ($i = 0; $i < 3; $i++)
		{
			$this->createSession();
		}

		for ($i = 0; $i < 4; $i++)
		{
			$this->createSessionWithUser($i);
		}

		$activities = Activity::guests()->get();

		$this->assertEquals(3, $activities->count());
	}

	/** @test */
	function it_retrieves_guests_who_were_active_within_the_last_3_seconds()
	{
		for ($i = 0; $i < 5; $i++)
		{
			$this->createSession(time() - $i);
		}

		$activities = Activity::guestsBySeconds(3)->get();

		$this->assertEquals(4, $activities->count());
	}

	/** @test */
	function it_retrieves_guests_who_were_active_within_the_last_3_minutes()
	{
		$secondsPerMinute = 60;

		$oneMinuteAgo = $secondsPerMinute * 1;
		$twoMinutesAgo = $secondsPerMinute * 2;
		$threeMinutesAgo = $secondsPerMinute * 3;
		$fourMinutesAgo = $secondsPerMinute * 4;
		$fiveMinutesAgo = $secondsPerMinute * 5;

		$this->createSession(time() - $oneMinuteAgo);
		$this->createSession(time() - $twoMinutesAgo);
		$this->createSession(time() - $threeMinutesAgo);
		$this->createSession(time() - $fourMinutesAgo);
		$this->createSession(time() - $fiveMinutesAgo);

		$activities = Activity::guestsByMinutes(3)->get();

		$this->assertEquals(3, $activities->count());
	}

	/** @test */
	function it_retrieves_guests_who_were_active_within_the_last_two_hours()
	{
		$secondsPerHour = 3600;

		$oneHourAgo = $secondsPerHour * 1;
		$twoHoursAgo = $secondsPerHour * 2;
		$threeHoursAgo = $secondsPerHour * 3;
		$fourHoursAgo = $secondsPerHour * 4;
		$fiveHoursAgo = $secondsPerHour * 5;

		$this->createSession(time() - $oneHourAgo);
		$this->createSession(time() - $twoHoursAgo);
		$this->createSession(time() - $threeHoursAgo);
		$this->createSession(time() - $fourHoursAgo);
		$this->createSession(time() - $fiveHoursAgo);

		$activities = Activity::guestsByHours(2)->get();

		$this->assertEquals(2, $activities->count());
	}
}
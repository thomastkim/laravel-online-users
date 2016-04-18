<?php

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;
use Kim\Activity\Activity;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->setupDatabase();
		$this->migrateTables();
	}

	public function setupDatabase()
	{
		$db = new Manager;
		$db->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
		$db->bootEloquent();
		$db->setAsGlobal();
	}

	protected function migrateTables()
	{
		Manager::schema()->create('users', function($table)
		{
			$table->increments('id');
			$table->timestamps();
		});
		Manager::schema()->create('sessions', function($table)
		{
			$table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('last_activity');
		});
	}

	protected function createSession($time = null)
	{
		$activity = new Activity;
		$activity->last_activity = (is_null($time)) ? time() : $time;
		$activity->save();

		return $activity;
	}

	protected function createSessionWithUser($i, $time = null)
	{
		$activity = new Activity;
		$activity->last_activity = (is_null($time)) ? time() : $time;
		$activity->user_id = $i;
		$activity->save();

		return $activity;
	}
}

class User extends Model
{
}
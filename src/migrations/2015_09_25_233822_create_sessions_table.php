<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // If they are already using the sessions database,
        // just alter it to add the user_id. Otherwise,
        // create the sessions table.
        if (Schema::hasTable('sessions') && !Schema::hasColumn('sessions', 'user_id'))
        {
            Schema::table('sessions', function (Blueprint $table)
            {
                $table->integer('user_id')->unsigned()->nullable();
                $table->foreign('user_id')->references('id')->on(config('auth.table'))->onDelete('cascade');
            });
        }
        else {
            Schema::create('sessions', function (Blueprint $table)
            {
                $table->string('id')->unique();

                $table->text('payload');
                $table->integer('last_activity');

                $table->integer('user_id')->unsigned()->nullable();
                $table->foreign('user_id')->references('id')->on(config('auth.table'))->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sessions');
    }
}

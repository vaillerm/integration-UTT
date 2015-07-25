<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewcomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('newcomers', function(Blueprint $table)
		{
			$table->increments('id')->unsigned()->unique();
			$table->string('first_name');
			$table->string('last_name');
			$table->string('email')->nullable();
			$table->string('phone')->nullable();
			$table->string('full_address')->nullable();
			$table->string('level');
			$table->integer('referral_id')->unsigned()->nullable();
			$table->integer('team_id')->unsigned()->nullable();

			$table->foreign('referral_id')->references('student_id')->on('referrals')->onDelete('set NULL')->onUpdate('cascade');
			$table->foreign('team_id')->references('id')->on('teams')->onDelete('set NULL')->onUpdate('cascade');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('newcomers');
	}

}

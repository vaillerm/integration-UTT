<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBirthColumnToNewcomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('newcomers', function(Blueprint $table)
		{
			$table->timestamp('birth')->after('password')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('newcomers', function(Blueprint $table)
		{
			$table->dropColumn('birth');
		});
	}

}

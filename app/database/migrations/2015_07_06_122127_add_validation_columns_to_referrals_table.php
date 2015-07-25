<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValidationColumnsToReferralsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('referrals', function(Blueprint $table)
		{
			$table->boolean('validated')->after('double_degree')->default(false);
			// Handle edition conflicts.
			$table->timestamp('started_validation_at')->after('validated')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('referrals', function(Blueprint $table)
		{
			$table->dropColumn('validated');
			$table->dropColumn('started_validation_at');
		});
	}

}

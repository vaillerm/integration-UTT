<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function(Blueprint $table)
		{
			$table->increments('id')->unsigned()->unique();
			$table->enum('mean', [
				'cash',
				'card',
				'check',
				'free'
			]);
			$table->integer('amount')->default(0);
			$table->string('bank')->nullable();
			$table->string('number')->nullable();
			$table->string('emitter')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payments');
	}

}

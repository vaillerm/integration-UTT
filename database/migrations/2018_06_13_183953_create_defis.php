<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create("challenges", function(Blueprint $table) {
			$table->increments("id");
			$table->string("name");
			$table->text("description");
			$table->integer("points");
			$table->date("deadline");
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::drop("challenges");
    }
}

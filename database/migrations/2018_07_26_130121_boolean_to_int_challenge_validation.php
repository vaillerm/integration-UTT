<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BooleanToIntChallengeValidation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('challenge_validations', function (Blueprint $table) {
			$table->smallInteger('validated')->change()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('challenge_validations', function (Blueprint $table) {
			$table->boolean('validated')->change()->nullable(true);
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChallengeValidationUpdateAuthor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('challenge_validations', function (Blueprint $table) {
			$table->unsignedInteger('update_author')->nullable(true);
			$table->foreign('update_author')->references('id')->on('users')->onDelete('set null');
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
			$table->dropForeign(['update_author']);
			$table->dropColumn('update_author');
        });
    }
}

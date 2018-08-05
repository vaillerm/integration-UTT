<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameChallengeTeamAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::rename('challenge_team', 'challenge_validations');
		Schema::table('challenge_validations', function(Blueprint $table) {
			$table->string('pic_url', 100);
		});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
		Schema::rename('challenge_validations', 'challenge_team');
		Schema::table('challenge_team', function(Blueprint $table) {
			$table->dropColumn('pic_url');
		});

    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrimaryKeyChallengeTeam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('challenge_team', function (Blueprint $table) {
			$table->primary(['team_id', 'challenge_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('challenge_team', function (Blueprint $table) {
			$table->dropPrimary(['team_id', 'challenge_id']);
        });
    }
}

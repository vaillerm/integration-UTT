<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChallengeTeam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_team', function (Blueprint $table) {
			$table->unsignedInteger("team_id");
			$table->unsignedInteger("challenge_id");
			$table->datetime("submitedOn");

			$table->foreign("team_id")->references("id")->on("teams")->onDelete("cascade");
			$table->foreign("challenge_id")->references("id")->on("challenges")->onDelete("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table("challenge_team", function(Blueprint $table) {
			$table->dropForeign("challenge_team_team_id_foreign");
			$table->dropForeign("challenge_team_challenge_id_foreign");
		});
        Schema::dropIfExists('challenge_team');
    }
}

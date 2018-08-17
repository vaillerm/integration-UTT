<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlayerChallengeChallengeValidations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('challenge_validations', function (Blueprint $table) {
            $table->string("challenge_type");
            $table->unsignedInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users")->nullable(true);
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
            $table->dropForeign(["user_id"]);
            $table->dropColumn("challenge_type");
            $table->dropColumn("user_id");
        });
    }
}

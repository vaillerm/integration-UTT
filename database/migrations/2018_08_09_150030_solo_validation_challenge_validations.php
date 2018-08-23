<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SoloValidationChallengeValidations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('challenge_validations', function (Blueprint $table) {
            /**
             * Only way I found to delete what I've done without droping the table
             */
            DB::unprepared('alter table challenge_validations drop foreign key challenge_team_team_id_foreign');
            DB::unprepared('alter table challenge_validations drop foreign key challenge_team_challenge_id_foreign');
            DB::unprepared('alter table challenge_validations drop primary key');
            ///////////////////////////////////
            
            $table->increments('id')->first();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('challenge_id')->references('id')->on('challenges')->onDelete('cascade');
            $table->unsignedInteger('user_id')->nullable(true)->change();
            $table->unsignedInteger('team_id')->nullable(true)->change();
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
            $table->dropPrimary(['id']);
            $table->dropColumn('id');
            $table->primary(['challenge_id', 'team_id']);
            $table->unsignedInteger('user_id')->nullable(false)->change();
            $table->unsignedInteger('team_id')->nullable(false)->change();
        });
    }
}

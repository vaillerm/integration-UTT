<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixRelationsAndTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->integer('respo_id')->unsigned()->change();
            $table->foreign('respo_id')->references('id')->on('students');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->string('parent_name')->change();
            $table->string('parent_phone')->change();
            $table->string('mission')->change();
            $table->string('device_token')->change();
            $table->integer('team_id')->unsigned()->change();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('referral_id')->references('id')->on('students');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('place')->change();
        });
        Schema::table('checkins', function (Blueprint $table) {
            $table->string('name')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['respo_id']);
        });
        Schema::table('students', function (Blueprint $table) {
            $table->text('parent_name')->change();
            $table->text('parent_phone')->change();
            $table->text('mission')->change();
            $table->text('device_token')->change();
            $table->dropForeign(['team_id']);
            $table->dropForeign(['referral_id']);
        });
        Schema::table('events', function (Blueprint $table) {
            $table->text('name')->change();
            $table->text('place')->change();
        });
        Schema::table('checkins', function (Blueprint $table) {
            $table->text('name')->change();
        });
    }
}

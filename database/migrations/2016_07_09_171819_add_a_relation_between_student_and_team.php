<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddARelationBetweenStudentAndTeam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->integer('team_id')->default(null)->nullable()->after('ce');
            $table->boolean('team_accepted')->default(false)->after('team_id');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->integer('respo_id')->after('img_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('team_id');
            $table->dropColumn('team_accepted');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('respo_id');
        });
    }
}

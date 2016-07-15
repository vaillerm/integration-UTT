<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFacebookLinkOnTeamAndMergeValidationFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('name_validated');
            $table->dropColumn('description_validated');
            $table->dropColumn('img_validated');
            $table->boolean('validated')->after('respo_id');
            $table->string('facebook')->after('img');
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
            $table->boolean('name_validated')->after('name');
            $table->boolean('description_validated')->after('description');
            $table->boolean('img_validated')->after('img');
            $table->dropColumn('validated');
            $table->dropColumn('facebook');
        });
    }
}

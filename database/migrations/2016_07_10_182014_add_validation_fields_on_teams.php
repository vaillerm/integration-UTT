<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValidationFieldsOnTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('teams', function(Blueprint $table)
		{
            $table->dropColumn('img_url');
            $table->boolean('name_validated')->after('name');
            $table->boolean('description_validated')->after('description');
            $table->string('img')->nullable()->after('description_validated');
            $table->boolean('img_validated')->after('img');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('teams', function(Blueprint $table)
		{
            $table->string('img_url')->nullable();
            $table->dropColumn('name_validated');
            $table->dropColumn('description_validated');
            $table->dropColumn('img');
            $table->dropColumn('img_validated');
        });
    }
}

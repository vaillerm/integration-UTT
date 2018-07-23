<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSubmittedOn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('challenge_team', function (Blueprint $table) {
			$table->renameColumn("submitedOn", "submittedOn");
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
			$table->renameColumn("submittedOn", "submitedOn");
        });
    }
}

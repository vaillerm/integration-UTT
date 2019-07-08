<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentaryAndPointPenaltyToPermUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perm_users', function (Blueprint $table) {
            $table->text('commentary');
            $table->integer('pointsPenalty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perm_users', function (Blueprint $table) {
            $table->dropColumn('commentary');
            $table->dropColumn('pointsPenalty');
        });
    }
}

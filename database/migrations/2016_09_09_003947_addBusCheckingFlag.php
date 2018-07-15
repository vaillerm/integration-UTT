<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusCheckingFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newcomers', function (Blueprint $table) {
            $table->boolean('checkin')->default(false);
        });
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('checkin')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newcomers', function (Blueprint $table) {
            $table->dropColumn('checkin');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('checkin');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEtuuttTokenFieldsOnStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('etuutt_access_token')->after('admin');
            $table->string('etuutt_refresh_token')->after('etuutt_access_token');
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
            $table->dropColumn('etuutt_access_token');
            $table->dropColumn('etuutt_refresh_token');
        });
    }
}

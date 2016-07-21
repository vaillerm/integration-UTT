<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNewcomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newcomers', function (Blueprint $table) {
            $table->dropColumn('level');
            $table->dropColumn('full_address');
            $table->dropColumn('sex');
        });
        Schema::table('newcomers', function (Blueprint $table) {
            $table->boolean('sex')->after('last_name');
            $table->string('login')->after('sex');
            $table->string('branch')->after('phone');
            $table->string('registration_email')->after('branch');
            $table->string('registration_cellphone')->after('registration_email');
            $table->string('registration_phone')->after('registration_cellphone');
            $table->string('registration_address')->after('registration_phone');
            $table->string('ine')->after('registration_address');
            $table->timestamp('last_login')->nullable()->after('team_id');
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
            $table->dropColumn('branch');
            $table->dropColumn('registration_email');
            $table->dropColumn('registration_cellphone');
            $table->dropColumn('registration_phone');
            $table->dropColumn('registration_address');
            $table->dropColumn('ine');
            $table->dropColumn('last_login');
            $table->dropColumn('sex');
            $table->dropColumn('login');
        });
        Schema::table('newcomers', function (Blueprint $table) {
            $table->string('sex');
            $table->string('level');
            $table->string('full_address');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUttAdmittedId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('admitted_id')->nullable()->unique();
            $table->boolean('wei_majority')->nullable();
            $table->boolean('sex')->nullable()->change();
            $table->dropColumn('ine');
            $table->dropColumn('registration_cellphone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admitted_id');
            $table->dropColumn('wei_majority');
            $table->string('ine')->nullable();
            $table->string('registration_cellphone')->nullable();
        });
    }
}

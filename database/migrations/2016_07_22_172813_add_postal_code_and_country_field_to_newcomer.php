<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostalCodeAndCountryFieldToNewcomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newcomers', function (Blueprint $table) {
            $table->string('country')->after('registration_phone');
            $table->integer('postal_code')->after('registration_phone');
            $table->dropColumn('registration_address');
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
            $table->dropColumn('country');
            $table->dropColumn('postal_code');
            $table->string('registration_address')->after('registration_phone');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCeAndVolunteerFieldToStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('ce')->default(false)->after('referral_validated_at');
            $table->boolean('volunteer')->default(false)->after('ce');
            $table->boolean('orga')->default(false)->after('volunteer');
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
            $table->dropColumn('ce');
            $table->dropColumn('volunteer');
            $table->dropColumn('orga');
        });
    }
}

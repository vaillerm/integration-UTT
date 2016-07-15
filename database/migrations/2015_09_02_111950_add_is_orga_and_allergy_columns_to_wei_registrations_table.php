<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsOrgaAndAllergyColumnsToWeiRegistrationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wei_registrations', function (Blueprint $table) {
            $table->string('allergy')->nullable();
            $table->boolean('is_orga')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wei_registrations', function (Blueprint $table) {
            $table->dropColumn('allergy');
            $table->dropColumn('is_orga');
        });
    }
}

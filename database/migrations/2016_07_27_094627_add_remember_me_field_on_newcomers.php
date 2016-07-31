<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRememberMeFieldOnNewcomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newcomers', function (Blueprint $table) {
            $table->string('remember_token')->after('team_id');
            $table->text('checklist')->after('team_id');
            $table->text('parent_name')->after('checklist');
            $table->text('parent_phone')->after('parent_name');
            $table->text('medical_allergies')->after('parent_phone');
            $table->text('medical_treatment')->after('medical_allergies');
            $table->text('medical_note')->after('medical_treatment');
            $table->boolean('referral_emailed')->after('medical_note');
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
            $table->dropColumn('remember_token');
            $table->dropColumn('checklist');
            $table->dropColumn('parent_name');
            $table->dropColumn('parent_phone');
            $table->dropColumn('medical_allergies');
            $table->dropColumn('medical_treatment');
            $table->dropColumn('medical_note');
            $table->dropColumn('referral_emailed');
        });
    }
}

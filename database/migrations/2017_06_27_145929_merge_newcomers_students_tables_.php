<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MergeNewcomersStudentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newcomers', function (Blueprint $table) {
            $table->dropForeign('newcomers_referral_id_foreign');
            $table->dropColumn('referral_id');
        });

        Schema::table('students', function (Blueprint $table) {

            // change the primary key of the 'students' table
            $table->integer('student_id')->change();
            $table->increments('id')->unsigned()->unique();

            // add a new column to know if this student is a newcomer or not
            $table->boolean('is_newcomer');

            // informations that are in 'newcomers' but not in 'students'
            $table->string('login')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('birth')->nullable();
            $table->string('registration_email')->nullable();
            $table->string('registration_cellphone')->nullable();
            $table->string('registration_phone')->nullable();
            $table->string('ine')->nullable();
            $table->integer('referral_id')->unsigned()->nullable();
            $table->text('checklist')->nullable();
            $table->text('parent_name')->nullable();
            $table->text('parent_phone')->nullable();
            $table->text('medical_allergies')->nullable();
            $table->text('medical_treatment')->nullable();
            $table->text('medical_note')->nullable();
            $table->boolean('referral_emailed')->nullable();
            $table->boolean('parent_authorization')->nullable();
            $table->string('remember_token')->nullable();
        });

        Schema::dropIfExists('newcomers');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayRelationsToStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('wei')->after('admin');
            $table->tinyInteger('wei_validated')->after('wei');
            $table->integer('wei_payment')->nullable()->unsigned()->after('wei_validated');
            $table->foreign('wei_payment')->references('id')->on('payments');
            $table->integer('sandwich_payment')->nullable()->unsigned()->after('wei_payment');
            $table->foreign('sandwich_payment')->references('id')->on('payments');
            $table->integer('guarantee_payment')->nullable()->unsigned()->after('sandwich_payment');
            $table->foreign('guarantee_payment')->references('id')->on('payments');
        });
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('wei');
            $table->dropColumn('wei_validated');
            $table->dropForeign('students_wei_payment_foreign');
            $table->dropColumn('wei_payment');
            $table->dropForeign('students_sandwich_payment_foreign');
            $table->dropColumn('sandwich_payment');
            $table->dropForeign('students_guarantee_payment_foreign');
            $table->dropColumn('guarantee_payment');
        });
    }
}

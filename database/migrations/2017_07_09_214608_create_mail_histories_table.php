<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id');
            //$table->foreign('student_id')->references('id')->on('students')->onDelete('set NULL');

            $table->unsignedInteger('mail_revision_id');
            //$table->foreign('mail_revision_id')->references('id')->on('mail_revisions')->onDelete('cascade');

            $table->string('mail');

            $table->unsignedInteger('mail_cron_id')->nullable();
            //$table->foreign('mail_cron_id')->references('id')->on('mail_crons')->onDelete('set NULL');

            $table->dateTime('sent_at')->default(\Carbon\Carbon::now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_histories');
    }
}

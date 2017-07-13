<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailCronsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_crons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lists');
            $table->dateTime('send_data')->default(\Carbon\Carbon::now());
            $table->unsignedInteger('mail_revision_id');
            //$table->foreign('mail_revision_id')->references('id')->on('mail_revisions')->onDelete('set NULL');
            $table->boolean('is_sent')->default(false);

            $table->unsignedInteger('created_by');
            //$table->foreign('created_by')->references('id')->on('students')->onDelete('set NULL');

            $table->integer('lists_size')->default(0);
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
        Schema::dropIfExists('mail_crons');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->longText('content')->nullable();
            $table->string('template')->nullable();
            $table->boolean('isPublicity')->default(true);

            $table->integer('parent')->unsigned()->nullable()->default(null);
            //$table->foreign('parent')->references('id')->on('mail_revisions')->onDelete('cascade');

            $table->integer('created_by');
            //$table->foreign('created_by')->references('id')->on('students')->onDelete('cascade');
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
        Schema::dropIfExists('mail_revisions');
    }
}

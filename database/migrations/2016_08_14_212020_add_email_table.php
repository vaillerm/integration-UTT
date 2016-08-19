<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject', 78);
            $table->text('template');
            $table->boolean('is_plaintext');
            $table->tinyInteger('list');
            $table->datetime('scheduled_for')->nullable();
            $table->boolean('started');
            $table->integer('done');
            $table->integer('total');
            $table->text('donelist');
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
        Schema::drop('emails');
    }
}

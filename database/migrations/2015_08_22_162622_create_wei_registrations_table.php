<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeiRegistrationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wei_registrations', function (Blueprint $table) {
            $table->increments('id')->unsigned()->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('birthdate')->default(DB::raw(' NOW() '));
            $table->boolean('gave_parental_authorization')->default(false);
            $table->boolean('complete')->default(false);
            $table->integer('payment_id')->unsigned()->nullable();
            $table->foreign('payment_id')->references('id')->on('payments');
            $table->integer('deposit_id')->unsigned()->nullable();
            $table->foreign('deposit_id')->references('id')->on('payments');
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
        Schema::drop('wei_registrations');
    }
}

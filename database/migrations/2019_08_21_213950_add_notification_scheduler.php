<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationScheduler extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('notification_crons', function (Blueprint $table) {
      $table->increments('id');
      $table->string('title');
      $table->string('message');
      $table->string('targets');
      $table->dateTime('send_date')->default(\Carbon\Carbon::now());
      $table->boolean('is_sent')->default(false);
      $table->integer('created_by')->nullable()->default(null)->unsigned()->index();
      $table->foreign('created_by')->references('id')->on('users');
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
    Schema::dropIfExists('notification_crons');
  }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('perms', function (Blueprint $table) {
      $table->increments('id');
      $table->text('description');
      $table->text('place');
      $table->boolean('free_join');
      $table->dateTime('start');
      $table->dateTime('end');
      $table->integer('nbr_permanenciers');
      $table->integer('perm_type_id')->unsigned()->index();
      $table->foreign('perm_type_id')->references('id')->on('perm_types')->onDelete('cascade');
      $table->timestamps();
    });
    Schema::create('perm_users', function (Blueprint $table) {
      $table->integer('perm_id')->unsigned()->index();
      $table->foreign('perm_id')->references('id')->on('perms')->onDelete('cascade');

      $table->integer('user_id')->unsigned()->index();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

      $table->boolean('respo')->default(false);
      $table->text('presence');
      $table->text('absence_reason');

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
    Schema::dropIfExists('perm_users');
    Schema::dropIfExists('perms');
  }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermTypesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('perm_types', function (Blueprint $table) {
      $table->increments('id');
      $table->text('name');
      $table->text('description');
      $table->integer('points');
      $table->timestamps();
    });
    Schema::create('perm_type_respos', function (Blueprint $table) {
      $table->integer('perm_type_id')->unsigned()->index();
      $table->foreign('perm_type_id')->references('id')->on('perm_types')->onDelete('cascade');

      $table->integer('user_id')->unsigned()->index();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
    Schema::dropIfExists('perm_types');
    Schema::dropIfExists('perm_type_respos');
  }
}

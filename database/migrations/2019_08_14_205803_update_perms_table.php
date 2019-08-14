<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePermsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('perms', function (Blueprint $table) {
      $table->dropColumn('free_join');
      $table->text('open')->nullable()->change();
    });
    Schema::table('perm_users', function (Blueprint $table) {
      $table->text('presence')->nullable()->change();
      $table->text('commentary')->nullable()->change();
      $table->text('absence_reason')->nullable()->change();
      $table->text('pointsPenalty')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('perms', function (Blueprint $table) {
      $table->boolean('free_join')->default(false);
      $table->dropColumn('open');
    });
  }
}

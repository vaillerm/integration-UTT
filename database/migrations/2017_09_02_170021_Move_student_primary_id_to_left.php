<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveStudentPrimaryIdToLeft extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `students` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `students` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT AFTER checkin");

    }
}

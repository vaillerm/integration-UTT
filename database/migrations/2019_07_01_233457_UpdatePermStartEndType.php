<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePermStartEndType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perms', function (Blueprint $table) {
            $table->text('start')->change();
            $table->text('end')->change();
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
            $table->timestamp('start')->change();
            $table->timestamp('end')->change();
        });
    }
}

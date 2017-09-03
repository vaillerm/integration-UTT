<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FreePaiment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE payments CHANGE COLUMN mean mean ENUM('cash', 'card', 'check', 'etupay', 'free')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE payments CHANGE COLUMN mean mean ENUM('cash', 'card', 'check', 'etupay')");
    }
}

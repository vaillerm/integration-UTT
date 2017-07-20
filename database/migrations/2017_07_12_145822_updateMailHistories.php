<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMailHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_histories', function (Blueprint $table) {
            $table->dateTime('open_at')->nullable();
            $table->enum('state', ['PENDING', 'SENT', 'ERROR'])->default('PENDING');
            $table->text('error_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mail_histories', function (Blueprint $table) {
            $table->dropColumn(['open_at', 'state', 'error_text']);
        });
    }
}

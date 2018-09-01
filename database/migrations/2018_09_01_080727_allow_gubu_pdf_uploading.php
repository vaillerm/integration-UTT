<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowGubuPdfUploading extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gubu_parts', function (Blueprint $table) {
            $table->binary('file');
            $table->dropColumn('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gubu_parts', function (Blueprint $table) {
            $table->text('content');
            $table->dropColumn('file');
        });
    }
}

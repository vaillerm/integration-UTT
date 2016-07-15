<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->integer('student_id')->unsigned()->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('surname')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('level')->nullable();
            $table->text('free_text')->nullable();
            $table->integer('max')->default(3);
            $table->enum('double_degree', [
                'IMEDD',
                'SSI',
                'IMSGA',
                'SMILES',
                'OSS',
                'ONT',
                'MERI',
                'Autre'
            ])->nullable();
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
        DB::drop('referrals');
    }
}

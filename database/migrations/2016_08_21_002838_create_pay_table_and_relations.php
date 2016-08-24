<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayTableAndRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newcomers', function (Blueprint $table) {
            $table->boolean('wei')->after('referral_emailed');
            $table->integer('wei_payment')->nullable()->unsigned()->after('wei');
            $table->foreign('wei_payment')->references('id')->on('payments');
            $table->integer('sandwich_payment')->nullable()->unsigned()->after('wei_payment');
            $table->foreign('sandwich_payment')->references('id')->on('payments');
            $table->integer('guarantee_payment')->nullable()->unsigned()->after('sandwich_payment');
            $table->foreign('guarantee_payment')->references('id')->on('payments');
            $table->boolean('parent_authorization')->after('guarantee_payment');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('mean');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->enum('type', [
                'payment',
                'guarantee'
            ])->after('id');
            $table->enum('mean', [
                'cash',
                'card',
                'check',
                'etupay'
            ])->after('type');
            $table->enum('state', [
                'started',
                'returned',
                'refused',
                'paid',
                'refunded'
            ])->after('amount');
            $table->text('informations')->after('state');

            $table->dropColumn('bank');
            $table->dropColumn('number');
            $table->dropColumn('emitter');
            $table->timestamps();
        });

        Schema::drop('wei_registrations');
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newcomers', function (Blueprint $table) {
            $table->dropColumn('wei');
            $table->dropForeign('newcomers_wei_payment_foreign');
            $table->dropColumn('wei_payment');
            $table->dropForeign('newcomers_sandwich_payment_foreign');
            $table->dropColumn('sandwich_payment');
            $table->dropForeign('newcomers_guarantee_payment_foreign');
            $table->dropColumn('guarantee_payment');
            $table->dropColumn('parent_authorization');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('mean');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('state');
            $table->dropColumn('informations');

            $table->enum('mean', [
                'cash',
                'card',
                'check',
                'free'
            ]);
            $table->string('bank')->nullable();
            $table->string('number')->nullable();
            $table->string('emitter')->nullable();
            $table->dropTimestamps();
        });


        Schema::create('wei_registrations', function (Blueprint $table) {
            $table->increments('id')->unsigned()->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('birthdate')->default(DB::raw(' NOW() '));
            $table->boolean('gave_parental_authorization')->default(false);
            $table->boolean('complete')->default(false);
            $table->integer('payment_id')->unsigned()->nullable();
            $table->foreign('payment_id')->references('id')->on('payments');
            $table->integer('deposit_id')->unsigned()->nullable();
            $table->foreign('deposit_id')->references('id')->on('payments');
            $table->timestamps();
        });
    }
}

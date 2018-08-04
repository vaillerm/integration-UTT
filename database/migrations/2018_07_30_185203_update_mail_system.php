<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMailSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     public function up()
     {
        Schema::rename('mail_revisions', 'mail_templates');
        Schema::table('mail_templates', function (Blueprint $table) {
            $table->dropColumn('parent');
            $table->unsignedInteger('created_by')->change();
            $table->foreign('created_by')->references('id')->on('users');
        });
        // //Note: Renaming columns in a table with a enum column is not currently supported
        DB::statement('ALTER TABLE `mail_histories` CHANGE `mail_revision_id` `mail_template_id` int unsigned not null');
        DB::statement('ALTER TABLE `mail_histories` CHANGE `student_id` `user_id` int unsigned not null');
        DB::statement('ALTER TABLE `mail_histories` CHANGE `sent_at` `sent_at` datetime default null');
        DB::statement('ALTER TABLE `mail_histories` CHANGE `state` `state` enum("PENDING","SENDING","SENT","ERROR") NOT NULL');
        Schema::table('mail_histories', function (Blueprint $table) {
            $table->foreign('mail_template_id')->references('id')->on('mail_templates');
            $table->foreign('mail_cron_id')->references('id')->on('mail_crons');
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('mail_crons', function (Blueprint $table) {
            $table->renameColumn('mail_revision_id', 'mail_template_id');
            $table->foreign('mail_template_id')->references('id')->on('mail_templates');
            $table->foreign('created_by')->references('id')->on('users');
            $table->renameColumn('send_data', 'send_date');
        });
        Schema::drop('emails');
     }

     /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
        Schema::table('mail_templates', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->integer('parent')->unsigned()->nullable()->default(null);
        });
        Schema::table('mail_templates', function (Blueprint $table) {
            $table->integer('created_by')->change();
        });
        Schema::rename('mail_templates', 'mail_revisions');


        Schema::table('mail_histories', function (Blueprint $table) {
            $table->dropForeign(['mail_template_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['mail_cron_id']);
        });
        //Note: Renaming columns in a table with a enum column is not currently supported
        DB::statement('ALTER TABLE `mail_histories` CHANGE `mail_template_id` `mail_revision_id` int unsigned not null');
        DB::statement('ALTER TABLE `mail_histories` CHANGE `user_id` `student_id` int unsigned not null');
        DB::statement('ALTER TABLE `mail_histories` CHANGE `state` `state` enum("PENDING","SENT","ERROR") NOT NULL');


        Schema::table('mail_crons', function (Blueprint $table) {
            $table->dropForeign(['mail_template_id']);
            $table->dropForeign(['created_by']);
        });
        Schema::table('mail_crons', function (Blueprint $table) {
            $table->renameColumn('mail_template_id', 'mail_revision_id');
            $table->renameColumn('send_date', 'send_data');
        });

        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject', 78);
            $table->text('template');
            $table->boolean('is_plaintext');
            $table->tinyInteger('list');
            $table->datetime('scheduled_for')->nullable();
            $table->boolean('started');
            $table->integer('done');
            $table->integer('total');
            $table->text('donelist');
            $table->timestamps();
        });
    }
}

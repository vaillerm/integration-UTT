<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameStudentsTableToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('students', 'users');
        Schema::rename('checkin_student', 'checkin_user');
        Schema::table('checkin_user', function(Blueprint $table) {
            $table->renameColumn('student_id', 'user_id');
        });
        Schema::table('messages', function(Blueprint $table) {
            $table->renameColumn('student_id', 'user_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('users', 'students');
        Schema::rename('checkin_user', 'checkin_student');
        Schema::table('checkin_student', function(Blueprint $table) {
            $table->renameColumn('user_id', 'student_id');
        });
        Schema::table('messages', function(Blueprint $table) {
            $table->renameColumn('user_id', 'student_id');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MergeReferralsAndAdminIntoAnUniqueUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove enum field first because of a doctrine issue :
        // https://github.com/laravel/framework/issues/1186
		Schema::table('referrals', function(Blueprint $table)
		{
            $table->dropColumn('double_degree');
        });

		Schema::table('referrals', function(Blueprint $table)
		{
            $table->string('postal_code')->change();
            $table->integer('level')->nullable()->change();
            $table->string('branch')->nullable()->after('country');
            $table->string('facebook')->nullable()->after('level');
            $table->renameColumn('free_text', 'referral_text');
            $table->renameColumn('max', 'referral_max');
            $table->renameColumn('started_validation_at', 'referral_validated_at');
			$table->dropColumn('validated');
            $table->boolean('referral')->default(false)->after('facebook');
            $table->integer('admin')->default(0)->after('started_validation_at');
			$table->boolean('sex')->after('last_name');
            $table->timestamp('last_login')->nullable()->after('admin');
		});
        Schema::dropIfExists('administrators');
        Schema::rename('referrals', 'students');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('students', function(Blueprint $table)
		{

            $table->integer('postal_code')->nullable()->change();
            $table->string('level')->nullable()->change();
            $table->dropColumn('branch');
            $table->dropColumn('facebook');
            $table->renameColumn('referral_text', 'free_text');
            $table->renameColumn('referral_max', 'max');
            $table->renameColumn('referral_validated_at', 'started_validation_at');
            $table->boolean('validated')->default(false);
            $table->dropColumn('referral');
            $table->dropColumn('admin');
            $table->dropColumn('sex');
            $table->dropColumn('last_login');
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
		});
		Schema::create('administrators', function(Blueprint $table)
		{
			$table->integer('student_id')->unique();
		});
        Schema::rename('students', 'referrals');
    }
}

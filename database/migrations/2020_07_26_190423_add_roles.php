<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AddRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the new db structure
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id')->unsigned()->unique();
            $table->string('name');
            $table->text('description');
            $table->boolean('show_in_form')->default(true);
            $table->integer('order')->nullable()->default(null)->unsigned();
            $table->timestamps();
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->boolean('assigned')->default(false);
            $table->boolean('user_requested')->default(false);
            $table->string('subrole')->nullable()->default(null);

            $table->unique(['role_id', 'user_id']);
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('user_id')->references('id')->on('users');
        });

        // Convert old data
        $preferenceToRoleId = [];
        $missionToRoleId = [];
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            // Convert volunteer preferences
            if ($user->volunteer_preferences) {
                $preferences = json_decode($user->volunteer_preferences);
                if ($preferences !== NULL && is_array($preferences) && count($preferences) > 0) {
                    foreach ($preferences as $preference) {
                        // Create a role with preference name as role name if it doesn't exists
                        if (!isset($preferenceToRoleId[$preference])) {
                            $id = DB::table('roles')->insertGetId([
                                'name' => $preference,
                                'show_in_form' => false,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]);
                            $preferenceToRoleId[$preference] = $id;
                        }
                        // Insert into the pivot table
                        DB::table('role_user')->insertOrIgnore([
                            'role_id' => $preferenceToRoleId[$preference],
                            'user_id' => $user->id,
                            'user_requested' => true,
                        ]);
                    }
                }
            }

            // Convert mission
            if (!empty($user->mission)) {
                // Create a role with preference name as role name
                if (!isset($missionToRoleId[$user->mission])) {
                    $id = DB::table('roles')->insertGetId([
                        'name' => $user->mission,
                        'order' => $user->mission_order,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                    $missionToRoleId[$user->mission] = $id;
                }
                // Insert into the pivot table
                DB::table('role_user')->insertOrIgnore([
                    'role_id' => $missionToRoleId[$user->mission],
                    'user_id' => $user->id,
                    'assigned' => true,
                    'subrole' => $user->mission_respo ? 'Respo' : null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        // Delete old fields
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('volunteer_preferences');
            $table->dropColumn('mission');
            $table->dropColumn('mission_order');
            $table->dropColumn('mission_respo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop new tables
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');

        // Re-create old fields without data inside
        Schema::table('users', function (Blueprint $table) {
            $table->longText('volunteer_preferences')->default('[]');
            $table->text('mission');
            $table->boolean('mission_respo')->default(false);
            $table->integer('mission_order')->default(0);
        });
    }
}

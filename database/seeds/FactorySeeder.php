<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 100)->create();
        factory(App\Models\Team::class, 5)->create();
		factory(App\Models\Challenge::class, 20)->create();

        // Round 2 (to use last created stuff as foreigner key)
        factory(App\Models\User::class, 100)->create();
        factory(App\Models\Team::class, 5)->create();
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Models\Student;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\Models\Student::class, 10)->create();
        //factory(App\Models\Newcomer::class, 10)->create();
        //factory(App\Models\Team::class, 10)->create();

        // Round 2 (to use last created stuff as foreigner key)
        //factory(App\Models\Student::class, 10)->create();
        //factory(App\Models\Newcomer::class, 10)->create();
        //factory(App\Models\Team::class, 10)->create();
		factory(App\Models\Challenge::class, 10)->create();
    }
}

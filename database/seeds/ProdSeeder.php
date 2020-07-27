<?php

use Illuminate\Database\Seeder;

class ProdSeeder extends Seeder
{
    /**
     * Run the database seeds that are usefull in production
     *
     * @return void
     */
    public function run()
    {
		$this->call(RoleSeeder::class);
    }
}

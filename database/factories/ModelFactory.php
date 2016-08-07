<?php

use App\Models\Team;
use App\Models\Student;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\Newcomer::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'sex' => $faker->boolean,
        'birth' => $faker->date,
        'branch' => $faker->randomElement(array('AII', 'ISI', 'MP', 'MTE', 'SI', 'SM', 'SRT', 'TC')),
        'registration_email' => $faker->safeEmail,
        'registration_cellphone' => '06.12.34.56.78',
        'registration_phone' => '03.12.34.56.78',
        'postal_code' => 10000,
        'country' => $faker->randomElement(array('france', 'FRANCE', 'CAMEROUN')),
        'ine' => '1000000000A',
        'referral_id' => Student::where('referral_validated', 1)->orderByRaw("RAND()")->take(1)->get()->first()->student_id,
        'team_id' => Team::where('validated', 1)->orderByRaw("RAND()")->take(1)->get()->first()->id,
        'remember_token' => str_random(10),
    ];
});

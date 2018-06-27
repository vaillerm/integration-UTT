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

/*$factory->define(App\Models\Newcomer::class, function (Faker\Generator $faker) {
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
        'referral_id' => function () {
            $student = Student::where('referral_validated', 1)->orderByRaw("RAND()")->take(1)->get()->first();
            if ($student) {
                return $student->student_id;
            } else {
                return null;
            }
        },
        'team_id' => function () {
            $team = Team::where('validated', 1)->orderByRaw("RAND()")->take(1)->get()->first();
            if ($team) {
                return $team->id;
            } else {
                return null;
            }
        },
        'remember_token' => str_random(10),
    ];
}); */

$factory->define(App\Models\Student::class, function (Faker\Generator $faker) {
    return [
        'student_id' => $faker->unique()->randomNumber(5),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'sex' => $faker->boolean,
        'surname' => $faker->firstName,
        'email' => $faker->safeEmail,
        'phone' => '03.12.34.56.78',
        'postal_code' => $faker->randomNumber(5),
        'city' => $faker->city,
        'country' => $faker->country,
        'branch' => $faker->randomElement(array('AII', 'ISI', 'MP', 'MTE', 'SI', 'SM', 'SRT', 'TC')),
        'level' => $faker->randomNumber(1),
        'facebook' => $faker->url,
        'referral' => $faker->boolean,
        'referral_text' => $faker->text,
        'referral_max' => $faker->numberBetween(1, 5),
        'referral_validated' => $faker->boolean,
        'ce' => $faker->boolean,
        'team_id' => function () {
            $team = Team::orderByRaw("RAND()")->take(1)->get()->first();
            if ($team) {
                return $team->id;
            } else {
                return null;
            }
        },
        'team_accepted' => $faker->boolean,
        'volunteer' => $faker->boolean,
        'orga' => $faker->boolean,
        'admin' => 0,
        'etuutt_access_token' => '',
        'etuutt_refresh_token' => '',
        'last_login' => $faker->datetime,
    ];
});

$factory->define(App\Models\Team::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(5, true),
        'description' => $faker->text,
        'img' => $faker->randomElement(array('jpeg', 'png', 'bmp', 'jpg')),
        'facebook' => $faker->url,
        'respo_id' => function () {
            return factory(App\Models\Student::class)->create(['ce' => 1, 'team_accepted' => 1])->student_id;
        },
        'branch' => $faker->randomElement(array('MM', null)),
        'validated' => $faker->boolean,
        'comment' => $faker->text
    ];
});

$factory->define(App\Models\Challenge::class, function(Faker\Generator $faker) {
	return [
		"name" => $faker->words(1, true),
		"description" => $faker->words(10, true),
		"points" => $faker->randomDigit,
		"deadline" => $faker->date
	];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'lastname' => $faker->lastName,
        'phone' => $faker->phoneNumber,
        'mail'=> $faker->email,
        'slug' => $faker->slug,
        'company_id' => factory(\App\Models\Company::class),
        'user_id' => factory(\App\Models\User::class),
    ];
});

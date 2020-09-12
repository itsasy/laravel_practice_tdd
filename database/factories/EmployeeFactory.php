<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'lastname' => $faker->lastName,
        'company' => 'San Bruno',
        'mail' => $faker->unique()->safeEmail,
        'phone' => $faker->unique()->phoneNumber
    ];
});

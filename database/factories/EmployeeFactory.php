<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use App\Models\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'lastname' => $faker->lastName,
        'company_id' => $faker->unique()->numberBetween(1, Company::count()),
        'mail' => $faker->unique()->safeEmail,
        'phone' => $faker->unique()->phoneNumber
    ];
});

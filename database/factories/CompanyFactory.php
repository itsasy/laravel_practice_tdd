<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'mail' => $faker->unique()->safeEmail,
        'logo' => UploadedFile::fake()->image('image.png', 100, 100),
        'website' => $faker->name()
    ];
});

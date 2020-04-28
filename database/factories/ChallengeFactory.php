<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Challenge;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Challenge::class, function (Faker $faker) {
    return [
        // 'user_id' => function() {
        //     return factory(User::class)->create()->id;
        // },
        'user_id' => $faker->randomElement([1,2]),
        'title' => $faker->unique()->word . ' ' . $faker->unique()->word,
        'description' => $faker->text(),
        'startTime' => now(),
        'file' => $faker->imageUrl,
        'location' => $faker->country,
        'amount' => $faker->randomDigit,
        'termsAccepted' => $faker->boolean,
    ];
});

<?php

use Faker\Generator as Faker;

$factory->define(App\Friend::class, function (Faker $faker) {
    $status = $faker->randomElement([
        'friend',
        'pending',
    ]);

    return [
        'user_1' => $faker->numberBetween(1, 11),
        'user_2' => $faker->numberBetween(1, 11),
        'status' => $status,
    ];
});

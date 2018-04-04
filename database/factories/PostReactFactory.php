<?php

use Faker\Generator as Faker;

$factory->define(App\PostReact::class, function (Faker $faker) {
    return [
        'owner' => $faker->numberBetween(1, 11),
        'post_id' => $faker->numberBetween(1, 20),
        'type' => $faker->randomElement(['Like', 'Love', 'Laugh']),
    ];
});

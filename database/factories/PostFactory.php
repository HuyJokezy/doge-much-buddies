<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'content' => $faker->text(280),
        'owner' => $faker->numberBetween(1, 11),
    ];
});

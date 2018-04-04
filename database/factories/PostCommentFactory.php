<?php

use Faker\Generator as Faker;

$factory->define(App\PostComment::class, function (Faker $faker) {
    return [
        'post_id' => $faker->numberBetween(1, 20),
        'comment' => $faker->text(280),
        'owner' => $faker->numberBetween(1, 11),
    ];
});

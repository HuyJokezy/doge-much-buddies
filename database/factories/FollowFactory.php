<?php

use Faker\Generator as Faker;

$factory->define(App\Follow::class, function (Faker $faker) {
    $user_id = $faker->numberBetween(1, 11);
    $user = \App\User::find($user_id);

    $all_dogs = \App\Dog::all();
    $all_dogs_id = [];
    foreach ($all_dogs as $dog) {
        $all_dogs_id[] = $dog->id;
    }

    $user_dogs = $user->dogs()->get();
    $user_dogs_id = [];
    foreach ($user_dogs as $dog) {
        $user_dogs_id[] = $dog->id;
    }
    $dogs_id = array_diff($all_dogs_id, $user_dogs_id);

    return [
        'user_id' => $faker->numberBetween(1, 11),
        'dog_id' => $faker->randomElement($dogs_id),
    ];
});

<?php

use Faker\Generator as Faker;

$factory->define(App\Dog::class, function (Faker $faker) {
    $breed = $faker->randomElement(['Mixed', 
        'Labrador Retriever',
        'German Shepherds',
        'Golden Retriever',
        'Bulldog',
        'Boxer',
        'Rottweiler',
        'Dachshund',
        'Husky',
        'Great Dane',
        'Doberman Pinschers',
        'Australian Shepherds',
        'Corgi',
        'Shiba']);
    $profile_pic = "dogs/$breed.jpg";
    return [
        'name' => $faker->firstName,
        'owner' => $faker->numberBetween(1, 11),
        'breed' => $breed,
        'gender' => $faker->randomElement(['Male', 'Female']),
        'profile_image' => $profile_pic,
    ];
});

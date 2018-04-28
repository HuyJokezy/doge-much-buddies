<?php

use Faker\Generator as Faker;

$factory->define(App\DogImage::class, function (Faker $faker) {
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
        'dog_id' => $faker->numberBetween(1, 20),
        'image' => $profile_pic,
    ];
});

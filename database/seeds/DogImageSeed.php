<?php

use Illuminate\Database\Seeder;

class DogImageSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 40; $i++){
            Factory(App\DogImage::class)->create();
        }
    }
}

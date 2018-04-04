<?php

use Illuminate\Database\Seeder;

class PostReactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 30; $i++){
            Factory(App\PostReact::class)->create();
        }
    }
}

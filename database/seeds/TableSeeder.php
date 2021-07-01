<?php

use App\Like;
use App\Recipe;
use App\User;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        Like::truncate();
        Recipe::truncate();

        $faker = \Faker\Factory::create();

        $password = Hash::make('1234');

        User::create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => $password,
        ]);

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $password,
            ]);
        }

        for ($i = 0; $i < 100; $i++) {
            Recipe::create([
                'name'=>$faker->word,
                'photos'=>$faker->randomDigit,
                'ingredients'=>$faker->word,
                'steps'=>$faker->paragraph
            ]);
        }

        for ($i = 0; $i < 500; $i++) {
            Like::create([
                'recipe_id'=>$faker->numberBetween(0, 100),
                'user_id'=>$faker->numberBetween(0,10)
            ]);
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 15; $i++) {
            Category::create([
                'category_id' => $faker->randomElement([1,2]),
                'name' => $faker->unique()->word . ' ' . $faker->unique()->word,
            ]);
        }
    }
}

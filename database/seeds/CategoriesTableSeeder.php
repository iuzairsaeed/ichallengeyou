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
        Category::create([
            'category_id' => null,
            'name' => 'Action',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Adventure',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Academics',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Echo',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Creativity',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Entertainment',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Health + Fitness',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Location-Based',
            'created_at' => now()
        ]);
	
    }
}

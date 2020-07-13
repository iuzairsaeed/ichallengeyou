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
            'name' => 'Adventure',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Animal',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Brave',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Charity',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Crazy',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Creative',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Ecologic',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Funny',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Movies',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Music',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Sports',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'Stupid',
            'created_at' => now()
        ]);
        Category::create([
            'category_id' => null,
            'name' => 'World Record',
            'created_at' => now()
        ]);
	
    }
}

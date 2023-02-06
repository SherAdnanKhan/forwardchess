<?php

use App\Models\Product\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();
        DB::table('products_categories')->truncate();

        $categories = [
            'Opening Books',
            'Endgame Books',
            'En Español',
            'Tactics Books',
            'General Strategy Books',
            'Game Collection Books',
            'Periodicals',
            'Coming Soon!',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category
            ]);
        }
    }
}

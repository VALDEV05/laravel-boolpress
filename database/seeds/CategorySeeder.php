<?php

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'programming',
            'storytelling',
            'coding',
            'comedy',
            'database',
            'Classics',
            'Didactic',
            'family',
            'trips',
            'DIY',
            'style and fashion',
            'news'
        ];


        foreach ($categories as $category ) {
            $_category = new Category;
            $_category->name= $category;
            $_category->slug= Str::slug($category);
            $_category->save();
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Cleansers', 'description' => 'Face and body cleansers'],
            ['name' => 'Moisturizers', 'description' => 'Face and body moisturizers'],
            ['name' => 'Serums', 'description' => 'Face serums and treatments'],
            ['name' => 'Masks', 'description' => 'Face masks and treatments'],
            ['name' => 'Sun Care', 'description' => 'Sunscreens and after-sun care'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

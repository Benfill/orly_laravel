<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and gadgets'
            ],
            [
                'name' => 'Clothing',
                'description' => 'Men and women clothing'
            ],
            [
                'name' => 'Books',
                'description' => 'Books and educational materials'
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Home decor and garden supplies'
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Sports equipment and outdoor gear'
            ],
            [
                'name' => 'Toys & Games',
                'description' => 'Toys and games for all ages'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Electronics
            [
                'name' => 'Laptop HP ProBook',
                'price' => 899.99,
                'stock' => 50,
                'description' => 'High-performance laptop for professionals',
                'image' => 'https://via.placeholder.com/300x300.png?text=Laptop',
                'category_id' => 1,
            ],
            [
                'name' => 'Wireless Mouse',
                'price' => 29.99,
                'stock' => 200,
                'description' => 'Ergonomic wireless mouse',
                'image' => 'https://via.placeholder.com/300x300.png?text=Mouse',
                'category_id' => 1,
            ],
            [
                'name' => 'USB-C Hub',
                'price' => 49.99,
                'stock' => 150,
                'description' => '7-in-1 USB-C hub with multiple ports',
                'image' => 'https://via.placeholder.com/300x300.png?text=USB+Hub',
                'category_id' => 1,
            ],

            // Clothing
            [
                'name' => 'Men\'s T-Shirt',
                'price' => 19.99,
                'stock' => 300,
                'description' => 'Comfortable cotton t-shirt',
                'image' => 'https://via.placeholder.com/300x300.png?text=T-Shirt',
                'category_id' => 2,
            ],
            [
                'name' => 'Women\'s Jeans',
                'price' => 59.99,
                'stock' => 150,
                'description' => 'Stylish slim-fit jeans',
                'image' => 'https://via.placeholder.com/300x300.png?text=Jeans',
                'category_id' => 2,
            ],
            [
                'name' => 'Winter Jacket',
                'price' => 129.99,
                'stock' => 80,
                'description' => 'Warm winter jacket for cold weather',
                'image' => 'https://via.placeholder.com/300x300.png?text=Jacket',
                'category_id' => 2,
            ],

            // Books
            [
                'name' => 'Laravel Best Practices',
                'price' => 39.99,
                'stock' => 100,
                'description' => 'Complete guide to Laravel development',
                'image' => 'https://via.placeholder.com/300x300.png?text=Laravel+Book',
                'category_id' => 3,
            ],
            [
                'name' => 'Clean Code',
                'price' => 44.99,
                'stock' => 75,
                'description' => 'A handbook of agile software craftsmanship',
                'image' => 'https://via.placeholder.com/300x300.png?text=Clean+Code',
                'category_id' => 3,
            ],

            // Home & Garden
            [
                'name' => 'Table Lamp',
                'price' => 34.99,
                'stock' => 120,
                'description' => 'Modern LED table lamp',
                'image' => 'https://via.placeholder.com/300x300.png?text=Lamp',
                'category_id' => 4,
            ],
            [
                'name' => 'Garden Tools Set',
                'price' => 79.99,
                'stock' => 60,
                'description' => 'Complete set of garden tools',
                'image' => 'https://via.placeholder.com/300x300.png?text=Garden+Tools',
                'category_id' => 4,
            ],

            // Sports & Outdoors
            [
                'name' => 'Yoga Mat',
                'price' => 24.99,
                'stock' => 200,
                'description' => 'Non-slip yoga mat',
                'image' => 'https://via.placeholder.com/300x300.png?text=Yoga+Mat',
                'category_id' => 5,
            ],
            [
                'name' => 'Camping Tent',
                'price' => 149.99,
                'stock' => 40,
                'description' => '4-person waterproof camping tent',
                'image' => 'https://via.placeholder.com/300x300.png?text=Tent',
                'category_id' => 5,
            ],

            // Toys & Games
            [
                'name' => 'Board Game - Chess',
                'price' => 29.99,
                'stock' => 100,
                'description' => 'Classic wooden chess set',
                'image' => 'https://via.placeholder.com/300x300.png?text=Chess',
                'category_id' => 6,
            ],
            [
                'name' => 'LEGO Building Set',
                'price' => 89.99,
                'stock' => 80,
                'description' => 'Creative LEGO building set',
                'image' => 'https://via.placeholder.com/300x300.png?text=LEGO',
                'category_id' => 6,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

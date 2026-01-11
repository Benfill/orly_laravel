<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        // Create carts for customers 1 and 2
        $cartsData = [
            [
                'customer_id' => 1,
                'items' => [
                    ['product_id' => 1, 'quantity' => 1], // Laptop
                    ['product_id' => 2, 'quantity' => 2], // Mouse
                ]
            ],
            [
                'customer_id' => 2,
                'items' => [
                    ['product_id' => 4, 'quantity' => 3], // T-Shirt
                    ['product_id' => 7, 'quantity' => 1], // Laravel Book
                ]
            ],
        ];

        foreach ($cartsData as $cartData) {
            $cart = Cart::create([
                'customer_id' => $cartData['customer_id'],
            ]);

            foreach ($cartData['items'] as $item) {
                $product = Product::find($item['product_id']);
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }
        }
    }
}

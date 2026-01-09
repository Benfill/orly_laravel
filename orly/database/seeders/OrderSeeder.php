<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $ordersData = [
            [
                'customer_id' => 1,
                'status' => 'completed',
                'items' => [
                    ['product_id' => 1, 'quantity' => 1, 'price' => 899.99],
                    ['product_id' => 2, 'quantity' => 1, 'price' => 29.99],
                ],
                'payment' => [
                    'payment_method' => 'credit_card',
                    'status' => 'paid',
                ]
            ],
            [
                'customer_id' => 2,
                'status' => 'pending',
                'items' => [
                    ['product_id' => 4, 'quantity' => 2, 'price' => 19.99],
                    ['product_id' => 5, 'quantity' => 1, 'price' => 59.99],
                ],
                'payment' => [
                    'payment_method' => 'paypal',
                    'status' => 'pending',
                ]
            ],
            [
                'customer_id' => 3,
                'status' => 'shipped',
                'items' => [
                    ['product_id' => 11, 'quantity' => 1, 'price' => 24.99],
                    ['product_id' => 9, 'quantity' => 2, 'price' => 34.99],
                ],
                'payment' => [
                    'payment_method' => 'credit_card',
                    'status' => 'paid',
                ]
            ],
        ];

        foreach ($ordersData as $orderData) {
            // Calculate total
            $total = 0;
            foreach ($orderData['items'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'customer_id' => $orderData['customer_id'],
                'status' => $orderData['status'],
                'total_amount' => $total,
            ]);

            // Create order items
            foreach ($orderData['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            // Create payment
            Payment::create([
                'order_id' => $order->id,
                'amount' => $total,
                'payment_method' => $orderData['payment']['payment_method'],
                'status' => $orderData['payment']['status'],
            ]);
        }
    }
}

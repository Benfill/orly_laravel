<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    /**
     * Create an order from a customer's cart
     */
    public function createOrderFromCart(int $customerId, string $paymentMethod): Order
    {
        return DB::transaction(function () use ($customerId, $paymentMethod) {
            $cart = Cart::where('customer_id', $customerId)->with('items.product')->first();

            if (!$cart || $cart->items->isEmpty()) {
                throw new Exception('Cart is empty.');
            }

            // Calculate total
            $total = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);

            // Create order
            $order = Order::create([
                'customer_id' => $customerId,
                'status' => 'pending',
                'total_amount' => $total,
            ]);

            // Create order items
            foreach($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
            }

            // Create payment record (initial status: pending)
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'status' => 'pending',
            ]);

            // Clear cart
            $cart->items()->delete();

            return $order;
        });
    }

    /**
     * Update payment status
     */
    public function processPayment(int $orderId, bool $success, ?string $transactionId = null)
    {
        $payment = Payment::where('order_id', $orderId)->firstOrFail();
        $order = $payment->order;

        if ($success) {
            $payment->update([
                'status' => 'paid',
                'transaction_id' => $transactionId,
                'paid_at' => now(),
            ]);

            $order->update(['status' => 'completed']);
            $order->refresh();
        } else {
            $payment->update(['status' => 'failed']);
            $payment->refresh();
            $order->update(['status' => 'failed']);
            $order->refresh();
        }

        return $payment;
    }

    /**
     * Get order with items and payment
     */
    public function getOrderDetails(int $orderId): Order
    {
        return Order::with(['items.product', 'payment'])->findOrFail($orderId);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Place order from authenticated user's cart
     */
    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|in:credit_card,paypal,cash',
        ]);

        $customerId = Auth::user()->customer->id;

        try {
            $order = $this->orderService->createOrderFromCart($customerId, $validated['payment_method']);
            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Process payment callback (simulate success/failure)
     */
    public function processPayment(Request $request, $orderId)
    {
        $validated = $request->validate([
            'success' => 'required|boolean',
            'transaction_id' => 'nullable|string',
        ]);

        try {
            $payment = $this->orderService->processPayment($orderId, $validated['success'], $validated['transaction_id']);
            return response()->json($payment);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Get order details
     */
    public function show($orderId)
    {
        try {
            $order = $this->orderService->getOrderDetails($orderId);
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    }
}

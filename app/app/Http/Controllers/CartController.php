<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $carts = $this->cartService->getAllCarts();
        return response()->json($carts);
    }

    public function show($id)
    {
        $cart = $this->cartService->getCartById($id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        return response()->json($cart);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
        ]);

        $cart = $this->cartService->createCart($validated);
        return response()->json($cart, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'items' => 'sometimes|array',
        ]);

        $cart = $this->cartService->updateCart($id, $validated);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        return response()->json($cart);
    }

    public function destroy($id)
    {
        $deleted = $this->cartService->deleteCart($id);
        if (!$deleted) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        return response()->json(['message' => 'Cart deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $payments = $this->paymentService->getAllPayments();
        return response()->json($payments);
    }

    public function show($id)
    {
        $payment = $this->paymentService->getPaymentById($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        return response()->json($payment);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        $payment = $this->paymentService->createPayment($validated);
        return response()->json($payment, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'order_id' => 'sometimes|exists:orders,id',
            'amount' => 'sometimes|numeric',
            'payment_method' => 'sometimes|string',
        ]);

        $payment = $this->paymentService->updatePayment($id, $validated);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return response()->json($payment);
    }

    public function destroy($id)
    {
        $deleted = $this->paymentService->deletePayment($id);
        if (!$deleted) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return response()->json(['message' => 'Payment deleted successfully']);
    }
}

<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private ?string $accessToken = null;

    public function __construct()
    {
        $this->baseUrl = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
    }

    /**
     * Get PayPal access token
     */
    private function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode("{$this->clientId}:{$this->clientSecret}"),
        ])->post("{$this->baseUrl}/v1/oauth2/token", [
            'grant_type' => 'client_credentials',
        ]);

        if ($response->failed()) {
            Log::error('PayPal authentication failed', ['response' => $response->json()]);
            throw new \Exception('Failed to authenticate with PayPal');
        }

        $data = $response->json();
        $this->accessToken = $data['access_token'] ?? throw new \Exception('Access token not found in response');

        return $this->accessToken;
    }

    /**
     * Create PayPal order
     */
    public function createOrder(Order $order): array
    {
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/v2/checkout/orders", [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => (string) $order->id,
                    'amount' => [
                        'currency_code' => config('services.paypal.currency', 'USD'),
                        'value' => number_format($order->total_amount, 2, '.', ''),
                    ],
                    'description' => "Order #{$order->id}",
                ],
            ],
            'application_context' => [
                'return_url' => route('paypal.success'),
                'cancel_url' => route('paypal.cancel'),
                'brand_name' => config('app.name'),
                'user_action' => 'PAY_NOW',
            ],
        ]);

        if ($response->failed()) {
            Log::error('PayPal order creation failed', [
                'order_id' => $order->id,
                'response' => $response->json(),
            ]);
            throw new \Exception('Failed to create PayPal order');
        }

        return $response->json() ?? [];
    }

    /**
     * Capture PayPal payment
     */
    public function capturePayment(string $paypalOrderId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/v2/checkout/orders/{$paypalOrderId}/capture");

        if ($response->failed()) {
            Log::error('PayPal capture failed', [
                'paypal_order_id' => $paypalOrderId,
                'response' => $response->json(),
            ]);
            throw new \Exception('Failed to capture PayPal payment');
        }

        return $response->json() ?? [];
    }

    /**
     * Get order details
     */
    public function getOrderDetails(string $paypalOrderId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json',
        ])->get("{$this->baseUrl}/v2/checkout/orders/{$paypalOrderId}");

        if ($response->failed()) {
            Log::error('Failed to get PayPal order details', [
                'paypal_order_id' => $paypalOrderId,
                'response' => $response->json(),
            ]);
            throw new \Exception('Failed to get PayPal order details');
        }

        return $response->json() ?? [];
    }

    /**
     * Refund a captured payment
     */
    public function refundPayment(string $captureId, float $amount): array
    {
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/v2/payments/captures/{$captureId}/refund", [
            'amount' => [
                'value' => number_format($amount, 2, '.', ''),
                'currency_code' => config('services.paypal.currency', 'USD'),
            ],
        ]);

        if ($response->failed()) {
            Log::error('PayPal refund failed', [
                'capture_id' => $captureId,
                'response' => $response->json(),
            ]);
            throw new \Exception('Failed to refund PayPal payment');
        }

        return $response->json() ?? [];
    }
}

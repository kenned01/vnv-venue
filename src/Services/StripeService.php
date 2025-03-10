<?php

namespace App\Services;

use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeService
{
    private string $stripeBaseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->stripeBaseUrl = $_ENV["STRIPE_BASE"] ?? "";
        $this->apiKey = $_ENV["STRIPE_KEY"] ?? "";
    }

    public function createCharge(string $token, float $amount, string $currency = "usd"): string
    {
        $url = "$this->stripeBaseUrl/charges"; // Example API
        $data = [
            "amount" => $amount,
            "currency" => $currency,
            "source" => $token
        ];

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Encode data for form submission
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey); // Authentication
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        // Execute cURL request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Output response
        if ($httpCode == 200) {
            echo "Payment Successful: " . $response;
        } else {
            echo "Error Processing Payment: " . $response;
        }

        return $response;
    }

    /**
     * @throws ApiErrorException
     */
    public function createChargeV1(string $token, float $amount, string $currency = "usd"): bool
    {
        $client = new StripeClient($this->apiKey);

        $charge = $client->charges->create([
            "amount" => $amount * 100,
            "currency" => $currency,
            "source" => $token
        ]);

        return ($charge->paid && !$charge->refunded);
    }
}
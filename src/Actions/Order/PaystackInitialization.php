<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class PaystackInitialization
{

    public function initializePaystackTransaction($amount, $email)
    {
        $secretKey = Config::get('paystack.secret_key');

        $url = 'https://api.paystack.co/transaction/initialize';

        $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])
            ->post($url, [
                'amount' => $amount,
                'email' => $email,
                'currency' => 'NGN',
            ]);

        return $response->json();
    }
    

    public function verifyPayment($reference)
    {
        $secretKey = Config::get('paystack.secret_key');
        $url = "https://api.paystack.co/transaction/verify/{$reference}";

        $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
            ])
            ->get($url);

        return $response->json();
    }
}

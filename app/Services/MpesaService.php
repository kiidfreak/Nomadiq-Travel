<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MpesaService
{
    private string $consumerKey;
    private string $consumerSecret;
    private string $shortcode;
    private string $passkey;
    private string $environment;
    private string $baseUrl;

    public function __construct()
    {
        $this->consumerKey = config('services.mpesa.consumer_key', env('MPESA_CONSUMER_KEY'));
        $this->consumerSecret = config('services.mpesa.consumer_secret', env('MPESA_CONSUMER_SECRET'));
        $this->shortcode = config('services.mpesa.shortcode', env('MPESA_SHORTCODE'));
        $this->passkey = config('services.mpesa.passkey', env('MPESA_PASSKEY'));
        $this->environment = config('services.mpesa.environment', env('MPESA_ENVIRONMENT', 'sandbox'));
        
        $this->baseUrl = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    /**
     * Get OAuth access token
     */
    public function getAccessToken(): string
    {
        $cacheKey = 'mpesa_access_token';
        
        // Check if token is cached
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

            if ($response->successful()) {
                $data = $response->json();
                $accessToken = $data['access_token'] ?? null;
                
                if ($accessToken) {
                    // Cache token for 55 minutes (tokens expire in 1 hour)
                    Cache::put($cacheKey, $accessToken, now()->addMinutes(55));
                    return $accessToken;
                }
            }

            Log::error('Failed to get M-Pesa access token', ['response' => $response->body()]);
            throw new \Exception('Failed to get M-Pesa access token');
        } catch (\Exception $e) {
            Log::error('M-Pesa OAuth error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate password for STK Push
     */
    private function generatePassword(): string
    {
        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);
        return $password;
    }

    /**
     * Initiate STK Push payment
     */
    public function stkPush(string $phoneNumber, float $amount, string $accountReference, string $transactionDescription): array
    {
        try {
            $accessToken = $this->getAccessToken();
            $timestamp = date('YmdHis');
            $password = $this->generatePassword();

            // Format phone number (remove + and ensure it starts with 254)
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            if (substr($phoneNumber, 0, 1) === '0') {
                $phoneNumber = '254' . substr($phoneNumber, 1);
            } elseif (substr($phoneNumber, 0, 3) !== '254') {
                $phoneNumber = '254' . $phoneNumber;
            }

            $payload = [
                'BusinessShortCode' => $this->shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => (int) $amount,
                'PartyA' => $phoneNumber,
                'PartyB' => $this->shortcode,
                'PhoneNumber' => $phoneNumber,
                'CallBackURL' => config('app.url') . '/api/mpesa/callback',
                'AccountReference' => $accountReference,
                'TransactionDesc' => $transactionDescription,
            ];

            $response = Http::withToken($accessToken)
                ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['ResponseCode']) && $data['ResponseCode'] == '0') {
                    Log::info('M-Pesa STK Push initiated successfully', [
                        'checkout_request_id' => $data['CheckoutRequestID'] ?? null,
                        'account_reference' => $accountReference,
                    ]);

                    return [
                        'success' => true,
                        'checkout_request_id' => $data['CheckoutRequestID'] ?? null,
                        'customer_message' => $data['CustomerMessage'] ?? 'Please check your phone to complete the payment',
                        'response_code' => $data['ResponseCode'] ?? null,
                    ];
                } else {
                    Log::error('M-Pesa STK Push failed', [
                        'response' => $data,
                        'account_reference' => $accountReference,
                    ]);

                    return [
                        'success' => false,
                        'error' => $data['errorMessage'] ?? $data['CustomerMessage'] ?? 'Payment request failed',
                    ];
                }
            }

            Log::error('M-Pesa STK Push HTTP error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to initiate payment. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('M-Pesa STK Push exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'An error occurred while processing your payment request.',
            ];
        }
    }

    /**
     * Query STK Push status
     */
    public function queryStkStatus(string $checkoutRequestId): array
    {
        try {
            $accessToken = $this->getAccessToken();
            $timestamp = date('YmdHis');
            $password = $this->generatePassword();

            $payload = [
                'BusinessShortCode' => $this->shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => $checkoutRequestId,
            ];

            $response = Http::withToken($accessToken)
                ->post("{$this->baseUrl}/mpesa/stkpushquery/v1/query", $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'result_code' => $data['ResultCode'] ?? null,
                    'result_desc' => $data['ResultDesc'] ?? null,
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to query payment status',
            ];
        } catch (\Exception $e) {
            Log::error('M-Pesa query error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}


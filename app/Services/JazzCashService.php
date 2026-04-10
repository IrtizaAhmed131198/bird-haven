<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JazzCashService
{
    private string $merchantId;
    private string $password;
    private string $integritySalt;
    private string $apiUrl;

    public function __construct()
    {
        $this->merchantId    = config('jazzcash.merchant_id');
        $this->password      = config('jazzcash.password');
        $this->integritySalt = config('jazzcash.integrity_salt');
        $this->apiUrl        = config('jazzcash.env') === 'live'
            ? config('jazzcash.live_url')
            : config('jazzcash.sandbox_url');
    }

    /**
     * Charge a JazzCash mobile wallet.
     *
     * @param  string $mobileNumber  Format: 03001234567
     * @param  float  $amount        Amount in PKR
     * @param  string $orderNumber   Unique order reference
     * @param  string $description
     * @return array  ['success' => bool, 'message' => string, 'response_code' => string]
     */
    public function charge(string $mobileNumber, float $amount, string $orderNumber, string $description = 'Bird Haven Order'): array
    {
        $now    = now();
        $expiry = now()->addMinutes(30);

        // JazzCash amount is in paisa (PKR × 100), no decimals
        $amountPaisa = (int) round($amount * 100);

        $params = [
            'pp_Version'            => '2.0',
            'pp_TxnType'            => 'MWALLET',
            'pp_Language'           => 'EN',
            'pp_MerchantID'         => $this->merchantId,
            'pp_SubMerchantID'      => '',
            'pp_Password'           => $this->password,
            'pp_BankID'             => 'TBANK',
            'pp_ProductID'          => 'RETL',
            'pp_TxnRefNo'           => 'T' . $now->format('YmdHis') . rand(1000, 9999),
            'pp_Amount'             => (string) $amountPaisa,
            'pp_TxnCurrency'        => 'PKR',
            'pp_TxnDateTime'        => $now->format('YmdHis'),
            'pp_BillReference'      => $orderNumber,
            'pp_Description'        => $description,
            'pp_TxnExpiryDateTime'  => $expiry->format('YmdHis'),
            'pp_MobileNumber'       => $mobileNumber,
            'pp_SecureHash'         => '',
        ];

        // Generate secure hash
        $params['pp_SecureHash'] = $this->generateHash($params);

        try {
            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($this->apiUrl, $params);

            $data = $response->json();

            $responseCode = $data['pp_ResponseCode'] ?? 'UNKNOWN';
            $responseMsg  = $data['pp_ResponseMessage'] ?? 'No response from JazzCash';

            if ($responseCode === '000') {
                return [
                    'success'       => true,
                    'message'       => 'Payment successful.',
                    'response_code' => $responseCode,
                    'txn_ref'       => $data['pp_TxnRefNo'] ?? null,
                ];
            }

            return [
                'success'       => false,
                'message'       => $this->friendlyMessage($responseCode, $responseMsg),
                'response_code' => $responseCode,
            ];

        } catch (\Exception $e) {
            Log::error('JazzCash API error: ' . $e->getMessage());

            return [
                'success'       => false,
                'message'       => 'Could not connect to JazzCash. Please try again or choose another payment method.',
                'response_code' => 'EXCEPTION',
            ];
        }
    }

    /**
     * Build HMAC-SHA256 secure hash.
     * JazzCash spec: sort params by key, concat as Salt&k1=v1&k2=v2...
     */
    private function generateHash(array $params): string
    {
        // Remove pp_SecureHash itself from hash calculation
        unset($params['pp_SecureHash']);

        // Sort alphabetically by key
        ksort($params);

        // Build string: IntegritySalt&key=value&key=value...
        $parts = [$this->integritySalt];
        foreach ($params as $key => $value) {
            if ($value !== '') {
                $parts[] = $value;
            }
        }

        $hashString = implode('&', $parts);

        return hash_hmac('sha256', $hashString, $this->integritySalt);
    }

    /**
     * Map JazzCash response codes to user-friendly messages.
     */
    private function friendlyMessage(string $code, string $original): string
    {
        return match ($code) {
            '001'  => 'Transaction failed. Please try again.',
            '007'  => 'Transaction declined by JazzCash.',
            '101'  => 'Incorrect JazzCash PIN entered. Please try again.',
            '110'  => 'Invalid mobile number. Please enter a valid JazzCash number.',
            '111'  => 'Transaction timed out. Please try again.',
            '115'  => 'Invalid transaction amount.',
            '121'  => 'Low balance in your JazzCash account.',
            '157'  => 'This JazzCash account is not registered for merchant payments.',
            '200'  => 'Merchant configuration error. Please contact support.',
            '301'  => 'Transaction already processed.',
            default => $original ?: 'Payment failed. Please try another method.',
        };
    }
}

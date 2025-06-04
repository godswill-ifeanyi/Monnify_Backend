<?php

namespace App\Services;

use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Log;

class MonnifyService
{
    protected $apiKey;
    protected $secretKey;
    protected $contractCode;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('monnify.api_key');
        $this->secretKey = config('monnify.secret_key');
        $this->contractCode = config('monnify.contract_code');
        $this->baseUrl = config('monnify.base_url');
    }

    public function authenticate()
    {
        $credentials = base64_encode("$this->apiKey:$this->secretKey");

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$this->baseUrl/api/v1/auth/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic $credentials",
                "Content-Type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);
        return $result['responseBody']['accessToken'] ?? null;
    }

    public function createReservedAccount($user)
    {
        $accessToken = $this->authenticate();
        if (!$accessToken) return null;

        $payload = [
            "accountReference" => $user->account_ref,
            "accountName" => strtoupper($user->chamber_name),
            "currencyCode" => "NGN",
            "contractCode" => $this->contractCode,
            "customerEmail" => $user->email,
            "customerName" => $user->name,
            "nin" => $user->nin,
            "getAllAvailableBanks" => false,
            "preferredBanks" => ["035"]
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$this->baseUrl/api/v2/bank-transfer/reserved-accounts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $accessToken",
                "Content-Type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);

        if ($result['requestSuccessful'] === true) {
            // Save to DB
            VirtualAccount::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'account_number' => $result['responseBody']['accounts'][0]['accountNumber'],
                    'account_name' => $result['responseBody']['accounts'][0]['accountName'],
                    'bank_name' => $result['responseBody']['accounts'][0]['bankName'],
                ]
            );
        }

        return $result ?? null;
    }

    public function disburseToClient($user, $amount, $narration = 'Chamber withdrawal')
    {
        $accessToken = $this->authenticate();
        if (!$accessToken) return null;

        // Optional: Check balance first
        $trust = TrustAccount::where('client_id', $user->id)->first();
        if (!$trust || $trust->balance < $amount) {
            return ['error' => 'Insufficient trust account balance.'];
        }

        $reference = 'TRF-' . now()->timestamp;

        $payload = [
            "amount" => $amount,
            "reference" => $reference,
            "narration" => $narration,
            "bankCode" => $user->bank_code, // Must be provided
            "accountNumber" => $user->account_number,
            "currency" => "NGN"
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$this->baseUrl/disbursements/single",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $accessToken",
                "Content-Type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);

        // If successful, deduct balance and optionally log transaction
        if (($result['responseCode'] ?? '') === '0') {
            $trust->decrement('balance', $amount);
            // Optional: log transaction here
        }

        return $result;
    }
}

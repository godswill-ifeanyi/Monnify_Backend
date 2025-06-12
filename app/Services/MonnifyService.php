<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\DisburseDetail;
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

    public function verifyBankAccount($accountNumber, $bankCode)
    {
        $accessToken = $this->authenticate();
        if (!$accessToken) return null;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "$this->baseUrl/api/v1/disbursements/account/validate?accountNumber=$accountNumber&bankCode=$bankCode");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,[
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json",
        ],);


        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);

        return $result ?? null;
    }

    public function disburseToClient($user, $destination, $amount, $narration = 'Chamber withdrawal')
    {
        $accessToken = $this->authenticate();
        if (!$accessToken) return null;

        $account = VirtualAccount::where('user_id', $user->id)->first();
        if (!$account || $account->balance < $amount) {
            $result = "insufficient balance";

            return $result;
        }

        $reference = $user->account_ref . now()->timestamp;

        $payload = [
            "amount" => $amount,
            "reference" => $reference,
            "narration" => $narration,
            "destinationBankCode" => $destination[0],
            "destinationAccountNumber" => $destination[1],
            "currency" => "NGN",
            "sourceAccountNumber" => $user->virtualAccount->account_number
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$this->baseUrl/api/v2/disbursements/single",
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
            $account->decrement('balance', $amount);

            $verify_disburse = $this->verifyDisbursement($result["reference"]);

            $transaction = new Transaction;
            $transaction->user_id = $user_id;
            $transaction->virtual_account_id = $account_id;
            $transaction->type = "debit";
            $transaction->amount = $amount;
            $transaction->reference = $reference;
            $transaction->narration = $narration;
            $transaction->is_completed = $verify_disburse["status"];
            $transaction->save();

            if ($transaction->is_completed != $verify_disburse["status"]) {
                $transaction->is_completed = $verify_disburse["status"];
                $transaction->update();
            }

            $disburse_detail = new DisburseDetail;
            $disburse_detail->transaction_id = $transaction->id;
            $disburse_detail->total_fee = $result['totalFee'];
            $disburse_detail->destionation_bank_name = $result['destionationBankName'];
            $disburse_detail->destionation_account_number = $result['destionationAccountNumber'];
            $disburse_detail->destionation_account_name = $result['destionationAccountName'];
            $disburse_detail->save();
        }

        return $result ?? null;
    }

    public function verifyDisbursement($reference) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "$this->baseUrl/api/v2/disbursements/single/summary?reference=$reference");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,[
            "Accept: application/json",
            "Content-Type: application/json",
        ],);

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);

        return $result ?? null;
    }
}

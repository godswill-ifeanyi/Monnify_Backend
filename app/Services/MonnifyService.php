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
    protected $mainAcctNumber;

    public function __construct()
    {
        $this->apiKey = config('monnify.api_key');
        $this->secretKey = config('monnify.secret_key');
        $this->contractCode = config('monnify.contract_code');
        $this->baseUrl = config('monnify.base_url');
        $this->mainAcctNumber = config('monnify.main_account_number');
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

    public function verifyNIN($nin) {
        $accessToken = $this->authenticate();
        if (!$accessToken) return null;

        $payload = [
            "nin" => $nin,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$this->baseUrl/api/v1/vas/nin-details",
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

        return $result ?? null;
    }

    public function getAdminAccount()
    {
        $accessToken = $this->authenticate();
        if (!$accessToken) return null;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "$this->baseUrl/api/v2/disbursements/wallet-balance?accountNumber=$this->mainAcctNumber");
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

    public function getBankAccounts()
    {
        $accessToken = $this->authenticate();
        if (!$accessToken) return null;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "$this->baseUrl/api/v1/banks");
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

    public function getTransactionStatus($reference)
    {
        $accessToken = $this->authenticate();
        if (!$accessToken) return null;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "$this->baseUrl/api/v2/transactions/$reference");
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

    public function disburseToClient($user, $destination, $amount, $narration)
    {
        $accessToken = $this->authenticate();
        if (!$accessToken) return null;

        $account = VirtualAccount::where('user_id', $user->id)->first();
        if (!$account || $account->balance < $amount) {
            $result = "insufficient balance";

            return $result;
        }

        $reference = $user->account_ref ."-". now()->timestamp;

        $payload = [
            "amount" => $amount,
            "reference" => $reference,
            "narration" => $narration ?? 'Chamber Withdrawal',
            "destinationBankCode" => $destination[0],
            "destinationAccountNumber" => $destination[1],
            "currency" => "NGN",
            "sourceAccountNumber" => $this->mainAcctNumber
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

            $transaction = new Transaction;
            $transaction->user_id = $user->id;
            $transaction->virtual_account_id = $user->virtualAccount->id;
            $transaction->type = "debit";
            $transaction->amount = $amount;
            $transaction->reference = $reference;
            $transaction->narration = $narration;
            $transaction->is_completed = $result['responseBody']["status"];
            $transaction->save();

            $disburse_detail = new DisburseDetail;
            $disburse_detail->transaction_id = $transaction->id;
            $disburse_detail->total_fee = $result['responseBody']['totalFee'];
            $disburse_detail->destination_bank_name = $result['responseBody']['destinationBankName'];
            $disburse_detail->destination_account_number = $result['responseBody']['destinationAccountNumber'];
            $disburse_detail->destination_bank_code = $result['responseBody']['destinationBankCode'];
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

    public function depositToClient($user, $amount, $description)
    {
        $accessToken = $this->authenticate();
        if (!$accessToken) return null;

        $reference = $user->account_ref . now()->timestamp;

        $payload = [
            "amount" => $amount,
            "customerName" => $user->name,
            "customerEmail" => $user->email,
            "paymentReference" => $reference,
            "paymentDescription" => $description ?? "Card Deposit",
            "currencyCode" => "NGN",
            'contractCode' => $this->contractCode,
            "paymentMethods" => ["CARD","ACCOUNT_TRANSFER"],
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$this->baseUrl/api/v1/merchant/transactions/init-transaction",
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

        return $result ?? null;
    }
}

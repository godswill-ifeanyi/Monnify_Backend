<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\DepositDetail;
use App\Models\VirtualAccount;
use App\Services\MonnifyService;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;

class WebhookController extends Controller
{
    use ApiResponseTrait;

     public function handle(Request $request)
    {
        $ip = $request->ip(); // Laravel detects the real IP

        $allowedIPs = [
            '35.242.133.146'
        ];

        if (!in_array($ip, $allowedIPs)) {
            return $this->error('Unauthorized IP', 403);
        }
        // Step 1: Verify Signature
        $payload = $request->getContent();
        $signature = $request->header('monnify-signature');

        $apiKey = config('monnify.api_key');
        $secretKey = config('monnify.secret_key');

        $computedHash = hash_hmac('sha512', $payload, $secretKey);

        if ($signature !== $computedHash) {
            return $this->error('Signature Invalid', 403);
        }

        $data = $request->json('eventData');

        // Step 2: Check if transaction already exists
        if (Transaction::where('reference', $data['paymentReference'])->exists()) {
            return $this->error('Duplicate Transaction', 409);
        }

        // Step 3: Credit client’s virtual account
        $accountReference = $data['product']['reference'];
        $amount = $data['amountPaid'];

        $user = User::where('account_ref', $accountReference)->first();
        if ($user) {
            $virtualAccount = VirtualAccount::where('user_id', $user->id)->first();
        }
        else {
            return $this->error('Account Not Found', 404);
        }

        // Step 4: Update balance and log transaction
        $virtualAccount->increment('balance', $amount);

        $transaction = new Transaction;
        $transaction->user_id = $user->id;
        $transaction->virtual_account_id = $virtualAccount->id;
        $transaction->amount = $amount;
        $transaction->type = 'credit';
        $transaction->reference = $data['transactionReference'];
        $transaction->narration = $data['paymentDescription'] ?? '';
        $transaction->is_completed = $data['paymentStatus'];
        $transaction->save();

        if ($data['paymentMethod'] == 'ACCOUNT_TRANSFER') {
            $deposit_detail = new DepositDetail;
            $deposit_detail->transaction_id = $transaction->id;
            $deposit_detail->sender_account_name = $data['paymentSourceInformation'][0]['accountName'];
            $deposit_detail->sender_account_number = $data['paymentSourceInformation'][0]['accountNumber'];
            $deposit_detail->sender_bank_code = $data['paymentSourceInformation'][0]['bankCode'];
            $deposit_detail->save();
        }

        return $this->success(new TransactionResource($transaction), 'Account Credited '.$amount, 200);
    }

    /* private function extractClientIdFromReference($reference): ?int
    {
        if (Str::startsWith($reference, 'trust-')) {
            $parts = explode('-', $reference);
            return $parts[1] ?? null;
        }

        return null;
    } */

}

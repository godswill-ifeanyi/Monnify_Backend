<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\DepositDetail;
use App\Models\VirtualAccount;
use App\Services\MonnifyService;
use App\Traits\ApiResponseTrait;
use App\Jobs\ProcessMonnifyWebhook;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;

class WebhookController extends Controller
{
    use ApiResponseTrait;

    /**
    *   Received funds.
    *
    * Notification is sent to this endpoint whenever a user receives a topup either by transfer or card. If everything is okay, you'll get a 201 Created response.
    *
    * Otherwise, the request will fail with an error, and a response listing the failed services.
    *
    * @response 201 {
    *       "status": "success",
    *       "message": "Account Credited N1000",
    *       "data": {
    *               "accountRef": "cliApp68400ed1b4b25",
    *               "accountName": "KIN",
    *               "accountNumber": 3396488285,
    *               "bankName": "Wema bank",
    *               "transactionDetails": {
    *                   "type": "credit",
    *                   "amount": "1000.00",
    *                   "narration": "Loan",
    *                   "reference": "cliApp68400ed1b4b25-7544734744",
    *                   "isCompleted": "FAILED",
    *                   "senderAccountName": "John Obi",
    *                   "senderAccountNumber": "4574757787",
    *                   "senderBankName": "035"
    *               },
    *               "createdAt": "2025-06-04T09:16:05.000000Z"
    *           }
 * }

    */

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

        // Process only successful transactions
        if (($request->eventType ?? '') == 'SUCCESSFUL_TRANSACTION') {
            $data = $request->json('eventData');

            // Step 2: Check if transaction already exists
            if (Transaction::where('reference', $data['transactionReference'])->exists()) {
                return $this->error('Duplicate Transaction', 409);
            }

            // Step 3: Credit client’s virtual account
            if ($data['product']['type'] == 'RESERVED_ACCOUNT'){
                $accountReference = $data['product']['reference'];
            }
            else if ($data['product']['type'] == 'WEB_SDK') {
                $accountReference = substr($data['product']['reference'],0,19);
            }
            $amount = $data['amountPaid'];

            $user = User::where('account_ref', $accountReference)->first();
            if ($user) {
                $virtualAccount = VirtualAccount::where('user_id', $user->id)->first();
            }
            else {
                return $this->error('Account Not Found', 404);
            }

            // Dispatch job to queue
            ProcessMonnifyWebhook::dispatch($data);
        }
        else {
            return $this->error('Event Type Not Supported', 400);
        }

        return $this->success(null, 'Webhook Received, Processing...', 201);

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

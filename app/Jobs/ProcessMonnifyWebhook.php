<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Transaction;
use App\Models\DepositDetail;
use App\Models\VirtualAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessMonnifyWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = $this->data;

        try {
            // 1. Resolve account reference
            if ($data['product']['type'] === 'RESERVED_ACCOUNT') {
                $accountReference = $data['product']['reference'];
            } elseif ($data['product']['type'] === 'WEB_SDK') {
                $accountReference = substr($data['product']['reference'], 0, 19);
            } else {
                Log::warning("Unknown product type: " . $data['product']['type']);
                return;
            }

            // 2. Find user & virtual account
            $user = User::where('account_ref', $accountReference)->first();
            if (!$user) {
                Log::warning("Webhook received for unknown accountRef: " . $accountReference);
                return;
            }

            $virtualAccount = VirtualAccount::where('user_id', $user->id)->first();
            if (!$virtualAccount) {
                Log::error("User {$user->id} has no virtual account");
                return;
            }

            $amountPaid = $data['amountPaid'];
            $arrears    = $virtualAccount->arrears;
            $deduction  = 0;

            // 3. Deduct arrears first
            if ($arrears > 0) {
                $deduction = min($arrears, $amountPaid);
                $virtualAccount->decrement('arrears', $deduction);

                Transaction::create([
                    'user_id'           => $virtualAccount->user_id,
                    'virtual_account_id'=> $virtualAccount->id,
                    'amount'            => $deduction,
                    'type'              => 'debit',
                    'reference'         => 'MONTHLY-FEE-' . now()->format('Ym') . '-' . $virtualAccount->user->account_ref,
                    'narration'         => 'Monthly Fee Deduction',
                    'is_completed'      => $amountPaid >= $arrears ? 'PAID' : 'PARTIALLY',
                ]);
            }

            // 4. Remaining balance goes to credit
            $remaining = $amountPaid - $deduction;
            if ($remaining > 0) {
                $virtualAccount->increment('balance', $remaining);

                $transaction = Transaction::create([
                    'user_id'           => $user->id,
                    'virtual_account_id'=> $virtualAccount->id,
                    'amount'            => $remaining,
                    'type'              => 'credit',
                    'reference'         => $data['transactionReference'],
                    'narration'         => $data['paymentDescription'] ?? '',
                    'is_completed'      => $data['paymentStatus'],
                ]);

                // 5. Deposit details
                $deposit_detail = new DepositDetail;
                $deposit_detail->transaction_id = $transaction->id;

                if ($data['paymentMethod'] == 'ACCOUNT_TRANSFER') {
                    $deposit_detail->sender_account_name = $data['paymentSourceInformation'][0]['accountName'] ?? 'UNKNOWN';
                    $deposit_detail->sender_account_number = $data['paymentSourceInformation'][0]['accountNumber'] ?? 'UNKNOWN';
                    $deposit_detail->sender_bank_code = $data['paymentSourceInformation'][0]['bankCode'] ?? 'UNKNOWN';
                } elseif ($data['paymentMethod'] == 'CARD') {
                    $deposit_detail->sender_account_name   = "CARD";
                    $deposit_detail->sender_account_number = "CARD";
                    $deposit_detail->sender_bank_code      = "CARD";
                }

                $deposit_detail->save();
            }

            Log::info("Webhook processed successfully for accountRef {$accountReference}, amount {$amountPaid}");

        } catch (\Throwable $e) {
            Log::error("Webhook processing failed: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data'  => $this->data
            ]);
        }
    }
}

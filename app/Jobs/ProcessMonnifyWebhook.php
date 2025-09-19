<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Transaction;
use App\Models\DepositDetail;
use Illuminate\Bus\Queueable;
use App\Models\DisburseDetail;
use App\Models\VirtualAccount;
use App\Events\TransactionCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessMonnifyWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $mainAcctName;
    protected $mainAcctNumber;
    protected $mainBankName;
    protected $mainBankCode;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->mainAcctName = config('monnify.main_account_name');
        $this->mainAcctNumber = config('monnify.main_account_number');
        $this->mainBankName = config('monnify.main_bank_name');
        $this->mainBankCode = config('monnify.main_bank_code');
    }

    public function handle(): void
    {
        $data = $this->data['eventData'];

        try {
            if (Transaction::where('reference', $data['transactionReference'])->exists()) {
                return; // Duplicate transaction, ignore
            }

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

            // 3. Always record deposit (CREDIT) first
            $creditTransaction = Transaction::create([
                'user_id'           => $user->id,
                'virtual_account_id'=> $virtualAccount->id,
                'amount'            => $amountPaid,
                'type'              => 'credit',
                'reference'         => $data['transactionReference'],
                'narration'         => $data['paymentDescription'] ?? 'Deposit',
                'is_completed'      => $data['paymentStatus'],
            ]);

            // Deposit details
            $deposit_detail = new DepositDetail;
            $deposit_detail->transaction_id = $creditTransaction->id;

            if ($data['paymentMethod'] === 'ACCOUNT_TRANSFER') {
                $deposit_detail->sender_account_name   = $data['paymentSourceInformation'][0]['accountName'] ?? 'UNKNOWN';
                $deposit_detail->sender_account_number = $data['paymentSourceInformation'][0]['accountNumber'] ?? 'UNKNOWN';
                $deposit_detail->sender_bank_code      = $data['paymentSourceInformation'][0]['bankCode'] ?? 'UNKNOWN';
            } elseif ($data['paymentMethod'] === 'CARD') {
                $deposit_detail->sender_account_name   = "CARD";
                $deposit_detail->sender_account_number = "CARD";
                $deposit_detail->sender_bank_code      = "CARD";
            } else {
                $deposit_detail->sender_account_name   = "UNKNOWN";
                $deposit_detail->sender_account_number = "UNKNOWN";
                $deposit_detail->sender_bank_code      = "UNKNOWN";
            }

            $deposit_detail->save();

            //event(new TransactionCreated($creditTransaction));

            // 4. Check and deduct arrears
            $arrears   = $virtualAccount->arrears;
            $deduction = 0;

            if ($arrears > 0) {
                $deduction = min($arrears, $amountPaid);

                $virtualAccount->decrement('arrears', $deduction);

                $debitTransaction = Transaction::create([
                    'user_id'           => $virtualAccount->user_id,
                    'virtual_account_id'=> $virtualAccount->id,
                    'amount'            => $deduction,
                    'type'              => 'debit',
                    'reference'         => 'MONTHLY-FEE-' . now()->format('YmdHis') . '-' . $virtualAccount->user->account_ref,
                    'narration'         => 'Monthly Fee Deduction',
                    'is_completed'      => $amountPaid >= $arrears ? 'PAID' : 'PARTIALLY',
                ]);

                $disburse_detail = new DisburseDetail;
                $disburse_detail->transaction_id = $debitTransaction->id;
                $disburse_detail->total_fee = 0.00;
                $disburse_detail->destination_bank_name = $this->mainBankName ?? 'UNKNOWN';
                $disburse_detail->destination_account_number = $this->mainAcctNumber ?? 'UNKNOWN';
                $disburse_detail->destination_bank_code = $this->mainBankCode ?? 'UNKNOWN';
                $disburse_detail->save();
            }

            // 5. Add only the remaining balance after arrears
            $remaining = $amountPaid - $deduction;
            if ($remaining > 0) {
                $virtualAccount->increment('balance', $remaining);
            }

            Log::info("Webhook processed: user={$user->id}, paid={$amountPaid}, arrearsDeducted={$deduction}, remaining={$remaining}");

        } catch (\Throwable $e) {
            Log::error("Webhook processing failed: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data'  => $this->data
            ]);
        }
    }
}

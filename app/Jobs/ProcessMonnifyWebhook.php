<?php

namespace App\Jobs; 

use App\Models\Transaction;
use App\Models\DepositDetail;
use Illuminate\Bus\Queueable;
use App\Models\VirtualAccount;
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
        if ($data['product']['type'] == 'RESERVED_ACCOUNT'){
            $accountReference = $data['product']['reference'];
        }
        else if ($data['product']['type'] == 'WEB_SDK') {
            $accountReference = substr($data['product']['reference'],0,19);
        }
        $amountPaid = $data['amountPaid'];

        $user = User::where('account_ref', $accountReference)->first();
        if ($user) {
            $virtualAccount = VirtualAccount::where('user_id', $user->id)->first();
        } else {
            return;
        }

        $arrears = $virtualAccount->arrears;
        $deduction = 0;

        if ($arrears > 0) {
            $deduction = min($arrears, $amountPaid);
            $virtualAccount->decrement('arrears', $deduction);

            Transaction::create([
                'user_id' => $virtualAccount->user_id,
                'virtual_account_id' => $virtualAccount->id,
                'amount' => $deduction,
                'type' => 'debit',
                'reference' => 'MONTHLY-FEE-' . now()->format('Ym') . '-' . $virtualAccount->user->account_ref,
                'narration' => 'Monthly Fee Deduction',
                'is_completed' => $amountPaid >= $arrears ? 'PAID' : 'PARTIALLY',
            ]);
        }

        // Remaining balance after arrears
        $remaining = $amountPaid - $deduction;
        if ($remaining > 0) {
            $virtualAccount->increment('balance', $remaining);

            $transaction = new Transaction;
            $transaction->user_id = $user->id;
            $transaction->virtual_account_id = $virtualAccount->id;
            $transaction->amount = $amountPaid;
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
            else if ($data['paymentMethod'] == 'CARD') {
                $deposit_detail = new DepositDetail;
                $deposit_detail->transaction_id = $transaction->id;
                $deposit_detail->sender_account_name = "CARD";
                $deposit_detail->sender_account_number = "CARD";
                $deposit_detail->sender_bank_code = "CARD";
                $deposit_detail->save();
            }
        }
    }
}

<?php

namespace App\Jobs;


use App\Models\MonthlyFee;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use App\Models\DisburseDetail;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeductMonthlyFee implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $virtualAccount;
    protected $monthlyFee;
    protected $mainAcctName;
    protected $mainAcctNumber;

    public function __construct(VirtualAccount $virtualAccount, $amount)
    {
        $this->virtualAccount = $virtualAccount;
        $this->monthlyFee = $amount;
        $this->mainAcctName = config('monnify.main_account_name');
        $this->mainAcctNumber = config('monnify.main_account_number');
    }

    public function handle(): void
    {
        $virtual_account = $this->virtualAccount->fresh();

        if (!$virtual_account) {
            return;
        }

        // Always add this month’s fee to arrears
        $virtual_account->increment('arrears', $this->monthlyFee);

        $arrears = $virtual_account->arrears;
        $balance = $virtual_account->balance;

        if ($balance > 0) {
            // Deduct as much as possible (partial or full)
            $deduction = min($arrears, $balance);

            $virtual_account->decrement('balance', $deduction);
            $virtual_account->decrement('arrears', $deduction);

            $transaction = Transaction::create([
                'user_id' => $virtual_account->user_id,
                'virtual_account_id' => $virtual_account->id,
                'amount' => $deduction,
                'type' => 'debit',
                'reference' => 'MONTHLY-FEE-' . now()->format('Ym') . '-' . $virtual_account->user->account_ref,
                'narration' => 'Monthly Fee Deduction',
                'is_completed' => $balance >= $arrears ? 'PAID' : 'PARTIALLY',
            ]);

            $disburse_detail = new DisburseDetail;
            $disburse_detail->transaction_id = $transaction->id;
            $disburse_detail->total_fee = 0.00;
            $disburse_detail->destination_bank_name = $this->mainAcctName;
            $disburse_detail->destination_account_number = $this->mainAcctNumber;
            $disburse_detail->destination_bank_code = 'UNKNOWN';
            $disburse_detail->save();
        }
    }
}

<?php

namespace App\Jobs;


use App\Models\VirtualAccount;
use App\Models\Transaction;
use App\Models\MonthlyFee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeductMonthlyFee implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $virtualAccount;
    protected $monthlyFee;

    public function __construct(VirtualAccount $virtualAccount, $amount)
    {
        $this->virtualAccount = $virtualAccount;
        $this->monthlyFee = $amount;
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
            
            Transaction::create([
                'user_id' => $virtual_account->user_id,
                'virtual_account_id' => $virtual_account->id,
                'amount' => $deduction,
                'type' => 'debit',
                'reference' => 'MONTHLY-FEE-' . now()->format('Ym') . '-' . $virtual_account->user->account_ref,
                'narration' => 'Monthly Fee Deduction',
                'is_completed' => $balance >= $arrears ? 'PAID' : 'PARTIALLY',
            ]);
        } 
    }
}

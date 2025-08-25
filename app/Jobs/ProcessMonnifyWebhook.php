<?php

namespace App\Jobs;

use App\Models\VirtualAccount;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessMonnifyWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $accountReference = $this->payload['eventData']['product']['reference'] ?? null;
        $amountPaid = $this->payload['eventData']['amountPaid'] ?? 0;

        $virtualAccount = virtualAccount::where('virtual_account_reference', $accountReference)->first();

        if (!$virtualAccount) {
            Log::warning("Webhook received for unknown account", ['reference' => $accountReference]);
            return;
        }

        $arrears = $virtualAccount->arrears;
        $deduction = 0;

        if ($arrears > 0) {
            $deduction = min($arrears, $amountPaid);
            $virtualAccount->decrement('arrears', $deduction);

            Transaction::create([
                'client_id' => $virtualAccount->client_id,
                'amount' => $deduction,
                'type' => 'debit',
                'reference' => 'ARREARS-CLEAR-' . uniqid(),
                'narration' => 'Auto arrears deduction from deposit',
                'status' => 'successful',
            ]);
        }

        // Remaining balance after arrears
        $remaining = $amountPaid - $deduction;
        if ($remaining > 0) {
            $virtualAccount->increment('balance', $remaining);

            Transaction::create([
                'client_id' => $virtualAccount->client_id,
                'amount' => $remaining,
                'type' => 'credit',
                'reference' => $this->payload['eventData']['transactionReference'] ?? uniqid(),
                'narration' => 'Deposit via Monnify',
                'status' => 'successful',
            ]);
        }

        Log::info("Monnify deposit processed", [
            'virtual_account_id' => $virtualAccount->id,
            'paid' => $amountPaid,
            'arrears_cleared' => $deduction,
            'balance_added' => $remaining
        ]);
    }
}

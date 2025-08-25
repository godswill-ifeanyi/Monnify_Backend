<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeductMonthlyFeeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deduct-monthly-fee-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $monthlyFee = \App\Models\MonthlyFee::where('id', 1)->value('amount') ?? 750.00;
        $virtualAccounts = \App\Models\VirtualAccount::all();
        foreach ($virtualAccounts as $account) {
            \App\Jobs\DeductMonthlyFee::dispatch($account, $monthlyFee);
        }
        $this->info('Monthly fee deduction jobs dispatched successfully.');
    }
}

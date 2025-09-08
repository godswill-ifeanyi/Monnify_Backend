<?php

use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $monthlyFee = \App\Models\MonthlyFee::where('id', 1)->value('amount') ?? 750.00;

        $schedule->call(function () use ($monthlyFee) {
            $virtualAccounts = \App\Models\VirtualAccount::all();
            foreach ($virtualAccounts as $account) {
                \App\Jobs\DeductMonthlyFee::dispatch($account, $monthlyFee);
            }
        })->monthlyOn(1, '00:00');
        //timezone('Africa/Lagos');
        //->everyMinute();

        /* $schedule->command('app:deduct-monthly-fee-command')->everyFiveMinutes() */
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

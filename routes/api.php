<?php

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\WebhookController;
use App\Http\Controllers\API\V1\TransactionController;
use App\Http\Controllers\API\V1\VirtualAccountController;

Route::prefix('v1')->group(function() {
    // Create Account
    Route::post('/create-account', [UserController::class, 'create']);
    Route::get('/account-details/{account_ref}', [UserController::class, 'show']);

    // Operations on Virtual Account
    Route::post('/verify-account', [VirtualAccountController::class, 'verify']);

    // Get Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{reference}', [TransactionController::class, 'show_one']);
    Route::get('/transactions/user/{account_ref}', [TransactionController::class, 'show']);

    // Send and Receive Funds
    Route::post('/disburse-funds', [TransactionController::class, 'disburse']);
    Route::post('/pay-online', [TransactionController::class, 'pay']);

    // Webhook
    Route::post('/webhook/credit', [WebhookController::class, 'handle']);

});

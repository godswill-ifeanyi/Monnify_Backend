<?php

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\AdminController;
use App\Http\Controllers\API\V1\WebhookController;
use App\Http\Controllers\API\V1\MonthlyFeeController;
use App\Http\Controllers\API\V1\TransactionController;
use App\Http\Controllers\API\V1\VirtualAccountController;

Route::prefix('v1')->group(function() {
    // Admin Monnify Account
    Route::get('/account-details/admin', [AdminController::class, 'show']);

    // Create Account
    Route::post('/create-account', [UserController::class, 'create']);
    Route::get('/account-details/users', [UserController::class, 'index']);
    Route::get('/account-details/{account_ref}', [UserController::class, 'show']);

    // Operations on Virtual Account
    Route::post('/verify-account', [VirtualAccountController::class, 'verify']);
    Route::get('/get-banks', [VirtualAccountController::class, 'get_banks']);

    // Get Transactions
    Route::get('/transactions', [TransactionController::class, 'show_all']);
    Route::get('/transaction/{reference}', [TransactionController::class, 'show_one']);
    Route::get('/transactions/user/{account_ref}', [TransactionController::class, 'show_all_by_user']);
    Route::get('/transaction/status/{reference}', [TransactionController::class, 'get_status']);

    // Send and Receive Funds
    Route::post('/disburse-funds', [TransactionController::class, 'disburse']);
    Route::get('/disburse-funds/status/{reference}', [TransactionController::class, 'get_disburse_status']);
    Route::post('/pay-online', [TransactionController::class, 'pay']);

    // Webhook
    Route::post('/webhook/credit', [WebhookController::class, 'handle']);

    // Monthly Fee
    Route::get('/monthly-fee', [MonthlyFeeController::class, 'index']);
    Route::put('/monthly-fee', [MonthlyFeeController::class, 'update']);


});

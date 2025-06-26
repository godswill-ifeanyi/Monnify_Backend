<?php

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\WebhookController;
use App\Http\Controllers\API\V1\TransactionController;
use App\Http\Controllers\API\V1\VirtualAccountController;

Route::prefix('v1')->group(function() {
    Route::post('/create-account', [UserController::class, 'create']);
    Route::post('/verify-account', [VirtualAccountController::class, 'verify']);
    Route::post('/disburse-funds', [VirtualAccountController::class, 'disburse']);
    Route::get('/transactions', [TransactionController::class, 'view']);

    Route::post('/webhook/credit', [WebhookController::class, 'handle']);
    Route::post('/webhook/test', [WebhookController::class, 'test']);
});

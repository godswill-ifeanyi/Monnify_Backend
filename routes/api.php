<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\VirtualAccountController;

Route::prefix('v1')->group(function() {
    Route::post('/create-account', [UserController::class, 'create']);
    Route::post('/verify-account', [VirtualAccountController::class, 'verify']);
});
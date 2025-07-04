<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\MonnifyService;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\PayRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawalRequest;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    use ApiResponseTrait;

    public function show_all(Request $request) {
        $transactions = Transaction::latest('id')->get();

        /* if ($search = $request->input('search')) {
            $transactions->where(function($q) use ($search) {
                $q->where('reference', $search);
            });
        } */

        $monnify = new MonnifyService();

        // Update Transaction Status
        foreach($transactions as $transaction) {
            if ($transaction->type == 'credit') {
                $transaction_status = $monnify->getTransactionStatus($transaction->reference);

                if ($transaction_status['requestSuccessful'] == true) {
                    if ($transaction_status['paymentStatus'] != $transaction->is_completed) {
                        $transaction->is_completed = $transaction_status['paymentStatus'];
                        $transaction->save();
                    }
                }
            }
            else if ($transaction->type == 'debit') {
                $monnify = new MonnifyService();
                $verify_disburse = $monnify->verifyDisbursement($disburse["reference"]);

                if ($verify_disburse['requestSuccessful'] == true) {
                    if ($verify_disburse['status'] != $transaction->is_completed) {
                        $transaction->is_completed = $verify_disburse['status'];
                        $transaction->save();
                    }
                }
            }
        }

        return $this->success(TransactionResource::collection($transactions), 'Transaction(s) Fetched Successfully', 200);
    }

    public function show_one($reference) {
        $transaction = Transaction::where('reference',$reference)->first();

        if (!$transaction) {
            return $this->error('Transaction Not Found', 404);
        }

        $monnify = new MonnifyService();

        // Update Transaction Status
        $transaction_status = $monnify->getTransactionStatus($transaction->reference);

        if ($transaction_status['requestSuccessful'] == true) {
            if ($transaction_status['paymentStatus'] != $transaction->is_completed) {
                $transaction->is_completed = $transaction_status['paymentStatus'];
                $transaction->save();
            }
        }

        return $this->success(new TransactionResource($transaction), 'Transaction Fetched Successfully', 200);
    }

    public function show_all_by_user($account_ref) {
        $user = User::where('account_ref', $account_ref)->first();
        $transactions = Transaction::where('user_id',$user->id)->latest('id')->get();

        if (!$transactions) {
            return $this->error('Transaction Not Found', 404);
        }

        $monnify = new MonnifyService();

        // Update Transaction Status
        foreach($transactions as $transaction) {
            $transaction_status = $monnify->getTransactionStatus($transaction->reference);

            if ($transaction_status['requestSuccessful'] == true) {
                if ($transaction_status['paymentStatus'] != $transaction->is_completed) {
                    $transaction->is_completed = $transaction_status['paymentStatus'];
                    $transaction->save();
                }
            }
        }

        return $this->success(TransactionResource::collection($transactions), 'Transaction(s) Fetched Successfully', 200);
    }

    public function get_status($reference) {
        $monnify = new MonnifyService();
        $transaction_status = $monnify->getTransactionStatus($reference);

        if ($transaction_status == null) {
            return $this->error('Something Went Wrong', 500);
        }
        else {
            if ($transaction_status['requestSuccessful'] == false) {
                return $this->error('Transaction Reference Not Found', 404);
            }
        }

        return $this->success(['isCompleted' => $transaction_status['paymentStatus']], 'Transaction Status Fetched Successfully', 200);
    }

    public function disburse(WithdrawalRequest $request) {
        $request->validated($request->all());

        $user = User::where('account_ref', $request->accountRef)->first();

        if (!$user) {
            return $this->error('Account Not Found', 404);
        }

        $bank_code = $request->destinationBankCode;
        $account_number = $request->destinationAccountNumber;

        $destination = [$bank_code, $account_number];

        $monnify = new MonnifyService();
        $disburse = $monnify->disburseToClient($user, $destination, $request->amount, $request->narration ?? null);

        if ($disburse == null) {
            return $this->error('Something Went Wrong', 500);
        }

        if ($disburse == "insufficient balance") {
            return $this->error('Insufficient Account Balance', 422);
        }

        if ($disburse['requestSuccessful'] == true) {

            return $this->success($disburse['responseBody'], 'Funds Successfully Disbursed', 200);
        }
        else {
            return $this->error(ucwords($disburse['responseMessage']),  500);
        }
    }

    public function get_disburse_status($reference) {
        $monnify = new MonnifyService();
        $verify_disburse = $monnify->verifyDisbursement($disburse["reference"]);

        if ($verify_disburse == null) {
            return $this->error('Something Went Wrong', 500);
        }

        if ($verify_disburse['requestSuccessful'] == true) {
            return $this->success(['isCompleted' => $verify_disburse['responseBody']['status']], 'Disburse Status Fetched Successfully', 200);
        }
        else {
            return $this->error(ucwords($verify_disburse['responseMessage']), 404);
        }
    }

    public function pay(PayRequest $request) {
        $data = $request->validated();

        $user = User::where('account_ref', $data['accountRef'])->first();

        if (!$user) {
            return $this->error('Account Not Found', 404);
        }

        $monnify = new MonnifyService();
        $deposit = $monnify->depositToClient($user, $data['amount'], $data['description'] ?? null);

        return response()->json($deposit);
    }
}

<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\MonnifyService;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawalRequest;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request) {
        $transactions = Transaction::latest('id')->get();

        /* if ($search = $request->input('search')) {
            $transactions->where(function($q) use ($search) {
                $q->where('reference', $search);
            });
        } */
        
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

    public function show($account_ref) {
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

    public function disburse(WithdrawalRequest $request) {
        $request->validated($request->all());

        $user = User::where('account_ref', $request->accountRef)->first();

        if (!$user) {
            return $this->error('User Account Ref Invalid', 403);
        }

        $bank_code = $request->destinationBankCode;
        $account_number = $request->destinationAccountNumber;

        $destination = [$bank_code, $account_number];

        $monnify = new MonnifyService();
        $disburse = $monnify->disburseToClient($user, $destination, $request->amount, $request->narration);

        if ($disburse == "insufficient balance") {
            return $this->error('Insufficient Account Balance', 422);
        }

        if ($disburse['requestSuccessful'] === true) {
            $monnify = new MonnifyService();
            $verify_disburse = $monnify->verifyDisbursement($disburse["reference"]);

            $response = [
                "amount"=> $disburse["amount"],
                "reference"=> $disburse["reference"],
                "totalFee"=> $disburse["totalFee"],
                "destinationAccountName"=> $disburse["destinationAccountName"],
                "destinationBankName"=> $disburse["destinationBankName"],
                "destinationAccountNumber"=> $disburse["destinationAccountNumber"],
                "destinationBankCode"=> $disburse["destinationBankCode"],
                "isCompleted" => $verify_disburse["status"],
                "dateCreated"=> $disburse["dateCreated"]
            ];

            return $this->success($response, 'Funds Successfully Disbursed', 200);
        }
        else {
            return $this->error(ucwords($disburse['responseMessage']),  500);
        }
    }

    public function pay(PayRequest $request) {
        $user = User::where('account_ref', $request->account_ref)->first();

        $monnify = new MonnifyService();
        $deposit = $monnify->depositToClient($user, $request->amount, $request->paymentDescription);

        return response()->json($deposit);
    }
}

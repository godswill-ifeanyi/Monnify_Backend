<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\DepositDetail;
use App\Models\DisburseDetail;
use App\Models\VirtualAccount;
use App\Services\MonnifyService;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\PayRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\WithdrawalRequest;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    use ApiResponseTrait;

    /**
    * Get all transactions
    *
    * If everything is okay, you'll get a 200 OK response.
    *
    * Otherwise, the request will fail with an error, and a response listing the failed services.
    *
    * @response 200 {
    *       "status": "success",
    *       "message": "Transaction(s) Fetched Successfully",
    *       "data": [
    *           {
    *               "accountRef": "cliApp68400ed1b4b25",
    *               "accountName": "KIN",
    *               "accountNumber": 3396488285,
    *               "bankName": "Wema bank",
    *               "transactionDetails": {
    *                   "type": "credit",
    *                   "amount": "100.00",
    *                   "narration": "Loan",
    *                   "reference": "cliApp68400ed1b4b25-7544734744",
    *                   "isCompleted": "FAILED",
    *                   "senderAccountName": "John Obi",
    *                   "senderAccountNumber": "4574757787",
    *                   "senderBankName": "035"
    *               },
    *               "createdAt": "2025-06-04T09:16:05.000000Z"
    *           },
    *           {
    *               "accountRef": "cliApp68400ed1b4b25",
    *               "accountName": "KIN",
    *               "accountNumber": 3396488285,
    *               "bankName": "Wema bank",
    *               "transactionDetails": {
    *                   "type": "debit",
    *                   "amount": "2000.00",
    *                   "totalFee": "10.00",
    *                   "narration": "Gift",
    *                   "reference": "cliApp68400ed1b4b25-734373733733",
    *                   "isCompleted": "PAID",
    *                   "receiverAccountName": "IFEANYI OKPANKU",
    *                   "receiverAccountNumber": "0691571803",
    *                   "receiverBankName": "Access Bank"
    *               },
    *               "createdAt": "2025-06-04T09:16:05.000000Z"
    *           }
    *       ]
    * }

     */

    public function show_all() {
        $transactions = Transaction::latest('id')->get();

        /* if ($search = $request->input('search')) {
            $transactions->where(function($q) use ($search) {
                $q->where('reference', $search);
            });
        } */

        // Update Transaction Status
        /* foreach($transactions as $transaction) {
            if ($transaction->type == 'credit') {
                $monnify = new MonnifyService();
                $transaction_status = $monnify->getTransactionStatus($transaction->reference);

                if ($transaction_status == null) {
                    return $this->error('Something Went Wrong', 500);
                }

                if ($transaction_status['requestSuccessful'] == true) {
                    if ($transaction_status['paymentStatus'] != $transaction->is_completed) {
                        $transaction->is_completed = $transaction_status['paymentStatus'];
                        $transaction->save();
                    }
                }
            }
            else if ($transaction->type == 'debit') {
                $monnify = new MonnifyService();
                $verify_disburse = $monnify->verifyDisbursement($transaction->reference);

                if ($verify_disburse == null) {
                    return $this->error('Something Went Wrong', 500);
                }

                if ($verify_disburse['requestSuccessful'] == true) {
                    if ($verify_disburse['status'] != $transaction->is_completed) {
                        $transaction->is_completed = $verify_disburse['status'];
                        $transaction->save();
                    }
                }
            }
        } */

        return $this->success(TransactionResource::collection($transactions), 'Transaction(s) Fetched Successfully', 200);
    }

    /**
    * Get one transaction
    *
    * Replace endpoint with the transaction reference. If everything is okay, you'll get a 200 OK response.
    *
    * Otherwise, the request will fail with an error, and a response listing the failed services.
    *
    * @urlParam reference string required The reference of the transaction. Example: cliApp68400ed1b4b25-7544734744
    * @response 200 {
    *       "status": "success",
    *       "message": "Transaction Fetched Successfully",
    *       "data": {
    *            "accountRef": "cliApp68400ed1b4b25",
    *            "accountName": "KIN",
    *            "accountNumber": 3396488285,
    *            "bankName": "Wema bank",
    *            "transactionDetails": {
    *                "type": "credit",
    *                "amount": "100.00",
    *                "narration": "Loan",
    *                "reference": "cliApp68400ed1b4b25-7544734744",
    *                "isCompleted": "FAILED",
    *                "senderAccountName": "John Obi",
    *                "senderAccountNumber": "4574757787",
    *                "senderBankName": "035"
    *            },
    *            "createdAt": "2025-06-04T09:16:05.000000Z"
    *        }
    * }

     */

    public function show_one($reference) {
        $transaction = Transaction::where('reference',$reference)->first();

        if (!$transaction) {
            return $this->error('Transaction Not Found', 404);
        }

        // $monnify = new MonnifyService();

        // Update Transaction Status
        /* $transaction_status = $monnify->getTransactionStatus($transaction->reference);

        if ($transaction_status == null) {
            return $this->error('Something Went Wrong', 500);
        }

        if ($transaction_status['requestSuccessful'] == true) {
            if ($transaction_status['paymentStatus'] != $transaction->is_completed) {
                $transaction->is_completed = $transaction_status['paymentStatus'];
                $transaction->save();
            }
        } */

        return $this->success(new TransactionResource($transaction), 'Transaction Fetched Successfully', 200);
    }

    public function get_one($reference) {
        $monnify = new MonnifyService();

        // Update Transaction Status
        $transaction = $monnify->getTransactionStatus($reference);

        if ($transaction == null) {
            return $this->error('Something Went Wrong', 500);
        }

        if ($transaction['requestSuccessful'] != true) {
            return $this->error('Transaction Not Found', 400);
        }

        $accountReference = substr($transaction['responseBody']['paymentReference'], 0, 19);

        // 2. Find user & virtual account
            $user = User::where('account_ref', $accountReference)->first();
            if (!$user) {
                return $this->error('User Not Found', 400);;
            }

            $virtualAccount = VirtualAccount::where('user_id', $user->id)->first();
            if (!$virtualAccount) {
                return $this->error('Reserved Acount Not Found', 400);;
            }

            $amountPaid = $transaction['responseBody']['amountPaid'];
            $settlementAmount = $transaction['responseBody']['settlementAmount'];

            // 3. Always record deposit (CREDIT) first
            $creditTransaction = Transaction::create([
                'user_id'           => $user->id,
                'virtual_account_id'=> $virtualAccount->id,
                'amount'            => $amountPaid,
                'type'              => 'credit',
                'reference'         => $transaction['responseBody']['transactionReference'],
                'narration'         => $transaction['responseBody']['paymentDescription'] ?? 'Deposit',
                'is_completed'      => $transaction['responseBody']['paymentStatus'],
            ]);

            // Deposit details
            $deposit_detail = new DepositDetail;
            $deposit_detail->transaction_id = $creditTransaction->id;

            if ($transaction['responseBody']['paymentMethod'] === 'CARD') {
                $deposit_detail->sender_account_name   = "CARD";
                $deposit_detail->sender_account_number = "CARD";
                $deposit_detail->sender_bank_code      = "CARD";
            } else {
                $deposit_detail->sender_account_name   = "UNKNOWN";
                $deposit_detail->sender_account_number = "UNKNOWN";
                $deposit_detail->sender_bank_code      = "UNKNOWN";
            }

            $deposit_detail->save();

            // 4. Check and deduct arrears
            $arrears   = $virtualAccount->arrears;
            $deduction = 0;

            if ($arrears > 0) {
                $deduction = min($arrears, $amountPaid);

                $virtualAccount->decrement('arrears', $deduction);

                $debitTransaction = Transaction::create([
                    'user_id'           => $virtualAccount->user_id,
                    'virtual_account_id'=> $virtualAccount->id,
                    'amount'            => $deduction,
                    'type'              => 'debit',
                    'reference'         => 'MONTHLY-FEE-' . now()->format('YmdHis') . '-' . $virtualAccount->user->account_ref,
                    'narration'         => 'Monthly Fee Deduction',
                    'is_completed'      => $amountPaid >= $arrears ? 'PAID' : 'PARTIALLY',
                ]);

                $disburse_detail = new DisburseDetail;
                $disburse_detail->transaction_id = $debitTransaction->id;
                $disburse_detail->total_fee = 0.00;
                $disburse_detail->destination_bank_name = $this->mainBankName ?? 'UNKNOWN';
                $disburse_detail->destination_account_number = $this->mainAcctNumber ?? 'UNKNOWN';
                $disburse_detail->destination_bank_code = $this->mainBankCode ?? 'UNKNOWN';
                $disburse_detail->save();


            }

            // 5. Add only the remaining balance after arrears
            $remaining = $settlementAmount - $deduction;
            if ($remaining > 0) {
                $virtualAccount->increment('balance', $remaining);
            }


        return $this->success([
                "user" => new UserResource($user),
                "paymentData" => [
                    //"date" => $transaction["responseBody"],
                    "date" => $transaction["responseBody"]["paidOn"],
                    "amountPaid" => $amountPaid,
                    "method" => "card payment"
                ]
            ], 
            'Transaction Fetched Successfully', 200);
        /* return response()->json([
            "user" => new UserResource($user),
            "amountPaid" => $amountPaid,
            "settlementAmount" => $transaction["responseBody"]["settlementAmount"]
        ]); */
    }

    /**
    * Get all user transactions
    *
    * Replace endpoint with the user's account ref. If everything is okay, you'll get a 200 OK response.
    *
    * Otherwise, the request will fail with an error, and a response listing the failed services.
    *
    * @urlParam account_ref string required The user's account_ref. Example: cliApp68400ed1b4b25
    * @response 200 {
    *       "status": "success",
    *       "message": "Transaction(s) Fetched Successfully",
    *       "data": [
    *           {
    *               "accountRef": "cliApp68400ed1b4b25",
    *               "accountName": "KIN",
    *               "accountNumber": 3396488285,
    *               "bankName": "Wema bank",
    *               "transactionDetails": {
    *                   "type": "credit",
    *                   "amount": "100.00",
    *                   "narration": "Loan",
    *                   "reference": "cliApp68400ed1b4b25-7544734744",
    *                   "isCompleted": "FAILED",
    *                   "senderAccountName": "John Obi",
    *                   "senderAccountNumber": "4574757787",
    *                   "senderBankName": "035"
    *               },
    *               "createdAt": "2025-06-04T09:16:05.000000Z"
    *           },
    *           {
    *               "accountRef": "cliApp68400ed1b4b25",
    *               "accountName": "KIN",
    *               "accountNumber": 3396488285,
    *               "bankName": "Wema bank",
    *               "transactionDetails": {
    *                   "type": "debit",
    *                   "amount": "2000.00",
    *                   "totalFee": "10.00",
    *                   "narration": "Gift",
    *                   "reference": "cliApp68400ed1b4b25-734373733733",
    *                   "isCompleted": "PAID",
    *                   "receiverAccountName": "IFEANYI OKPANKU",
    *                   "receiverAccountNumber": "0691571803",
    *                   "receiverBankName": "Access Bank"
    *               },
    *               "createdAt": "2025-06-04T09:16:05.000000Z"
    *           }
    *       ]
    * }

     */

    public function show_all_by_user($account_ref) {
        $user = User::where('account_ref', $account_ref)->first();

        if (!$user) {
            return $this->error('Account Not Found', 404);
        }

        $transactions = Transaction::where('user_id',$user->id)->latest('id')->get();

        if (!$transactions) {
            return $this->error('User Transaction Not Found', 404);
        }

        // Update Transaction Status
        /* $monnify = new MonnifyService();
        foreach($transactions as $transaction) {
            if ($transaction->type == 'credit') {
                $monnify = new MonnifyService();
                $transaction_status = $monnify->getTransactionStatus($transaction->reference);

                if ($transaction_status == null) {
                    return $this->error('Something Went Wrong', 500);
                }

                if ($transaction_status['requestSuccessful'] == true) {
                    if ($transaction_status['paymentStatus'] != $transaction->is_completed) {
                        $transaction->is_completed = $transaction_status['paymentStatus'];
                        $transaction->save();
                    }
                }
            }
            else if ($transaction->type == 'debit') {
                $monnify = new MonnifyService();
                $verify_disburse = $monnify->verifyDisbursement($transaction->reference);

                if ($verify_disburse == null) {
                    return $this->error('Something Went Wrong', 500);
                }

                if ($verify_disburse['requestSuccessful'] == true) {
                    if ($verify_disburse['status'] != $transaction->is_completed) {
                        $transaction->is_completed = $verify_disburse['status'];
                        $transaction->save();
                    }
                }
            }
        } */

        return $this->success(TransactionResource::collection($transactions), 'Transaction(s) Fetched Successfully', 200);
    }

    /**
    * Get transaction status
    *
    * Replace endpoint with the transaction reference. If everything is okay, you'll get a 200 OK response.
    *
    * Otherwise, the request will fail with an error, and a response listing the failed services.
    *
    * @urlParam reference string required The reference of the transaction. Example: MNFY|02|20250704161048|000089
    * @response 200 {
    *       "status": "success",
    *       "message": "Transaction Status Fetched Successfully",
    *       "data": {
    *            "isCompleted": "PAID",
    *       }
    * }

     */

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

    /**
    *   Disburse(withdraw) funds.
    *
    * Send the required parameters as JSON. If everything is okay, you'll get a 201 Created response.
    *
    * Otherwise, the request will fail with an error, and a response listing the failed services.
    *
    * @response 201 {
    *       "status": "success",
    *       "message": "Funds Successfully Disbursed",
    *       "data": {
    *           "amount": 350,,
    *           "reference": "cliApp6867b27d3a2c3-1751707790",
    *           "status": "PENDING_AUTHORIZATION",
    *           "dateCreated": "2025-07-05T09:29:51.274+00:00",
    *           "totalFee": 35,
    *           "destinationBankName": "Access bank",
    *           "destinationAccountNumber": "0691571803",
    *           "destinationBankCode": "044"
    *       }
 * }

    */
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

            return $this->success($disburse['responseBody'], 'Funds Successfully Disbursed', 201);
        }
        else {
            return $this->error(ucwords($disburse['responseMessage']),  500);
        }
    }

    /**
    * Get disburse transaction status
    *
    * Replace endpoint with the disburse transaction reference. If everything is okay, you'll get a 200 OK response.
    *
    * Otherwise, the request will fail with an error, and a response listing the failed services.
    *
    * @urlParam reference string required The reference of the disburse transaction. Example: cliApp68400ed1b4b25-7544734744
    * @response 200 {
    *       "status": "success",
    *       "message": "Transaction Status Fetched Successfully",
    *       "data": {
    *            "isCompleted": "PAID",
    *       }
    * }

     */
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

    /**
    *   Topup with card.
    *
    * Send the required parameters as JSON. If everything is okay, you'll get a 200 OK response.
    *
    * Then redirect user to the checkout URL to complete payment.
    *
    * @response 200 {
    *       "status": "success",
    *       "message": "Successful, Redirect To Checkout",
    *       "data": {
    *           "checkoutURL": "https://sandbox.sdk.monnify.com/checkout/MNFY|08|20250705105040|000183",
    *       }
 * }

    */
    public function pay(PayRequest $request) {
        $data = $request->validated();

        $user = User::where('account_ref', $data['accountRef'])->first();

        if (!$user) {
            return $this->error('Account Not Found', 404);
        }

        $monnify = new MonnifyService();
        $deposit = $monnify->depositToClient($user, $data['amount'], $data['description'] ?? null);

        return $this->success(['checkoutURL' => $deposit['responseBody']['checkoutUrl']], 'Successful, Proceed To Checkout', 200);
    }

    /**
     * Fetch the last processed credit transaction.
     */
    public function latest_credit()
    {
        $transaction = Cache::get('latest_credit_transaction');

        if (!$transaction) {
            return $this->error('No Recent Credit Transaction Found', 404);
        }

        return $this->success($transaction, 'Successful, Credit Transaction Received', 200);
    }

    public function latest_debit()
    {
        $transaction = Cache::get('latest_debit_transaction');

        if (!$transaction) {
            return $this->error('No Recent Debit Transaction Found', 404);
        }

        return $this->success($transaction, 'Successful, Debit Transaction Received', 200);
    }
}

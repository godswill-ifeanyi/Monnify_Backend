<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\MonnifyService;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawalRequest;

class TransactionController extends Controller
{
    use ApiResponseTrait;
    
    public function view() {
        $transactions = Transaction::all();

        return $this->success($transactions, 'Funds Successfully Disbursed', 200);
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
}

<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Services\MonnifyService;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyBankAccountRequest;

class VirtualAccountController extends Controller
{
    use ApiResponseTrait;

/**
*   Verify any bank account.
*
*
    * @response 200 {
*        "status": "success",
*        "message": "Account Details Valid",
*        "data": {
*           "accountName": "IFEANYI GODSWILL OKPANKU"
*       }
 * }


*   
*/

    public function verify(VerifyBankAccountRequest $request) {
        $request->validated($request->all());

        $monnify = new MonnifyService();
        $response = $monnify->verifyBankAccount($request->accountNumber, $request->bankCode);

        // Handle for when null
        if ($response["requestSuccessful"] == true) {
            $accountName = $response["responseBody"]["accountName"];

            return $this->success(['accountName' => $accountName], 'Account Details Valid', 200);
        }
        else {
            return $this->error('Account Details Invalid', 404);
        } 

    }
}

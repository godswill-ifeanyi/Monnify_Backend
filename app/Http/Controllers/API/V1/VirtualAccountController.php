<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
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
*   Send the required parameters as JSON. If everything is okay, you'll get a 200 OK response.
*
*   Otherwise, the request will fail with an error, and a response listing the failed services.
*   @response 200 {
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

        if ($response == null) {
            return $this->error('Something Went Wrong', 500);
        }
        else {
            if ($response["requestSuccessful"] == true) {
                $accountName = $response["responseBody"]["accountName"];

                return $this->success(['accountName' => $accountName], 'Account Details Valid', 200);
            }
            else {
                return $this->error('Account Details Invalid', 404);
            }
        }
    }

    /**
    * Get Nigerian banks details
    *
    * If everything is okay, you'll get a 200 OK response.
    *
    * Otherwise, the request will fail with an error, and a response listing the failed services.
    *
    * @response 200 {
    *       "status": "success",
    *       "message": "Banks Details Fetched Successfully",
    *       "data": [
    *            {
    *                "name": "9JAPAY MICROFINANCE BANK",
    *                "code": "090629",
    *                "ussdTemplate": null,
    *                "baseUssdCode": null,
    *                "transferUssdTemplate": null,
    *                "bankId": null,
    *                "nipBankCode": "090629"
    *             },
    *             {
    *                 "name": "Access bank",
    *                 "code": "044",
    *                 "ussdTemplate": "*901*Amount*AccountNumber#",
    *                 "baseUssdCode": "*901#",
    *                 "transferUssdTemplate": "*901*AccountNumber#",
    *                 "bankId": null,
    *                 "nipBankCode": "000014"
    *              }
    *       ]
    * }

     */

    public function get_banks() {
        $monnify = new MonnifyService();
        $response = $monnify->getBankAccounts();

        if ($response == null) {
            return $this->error('Something Went Wrong', 500);
        }
        else {
            if ($response["requestSuccessful"] == true) {

                return $this->success($response["responseBody"], 'Banks Details Fetched Successfully', 200);
            }
            else {
                return $this->error(ucwords($response["responseMessage"]), 404);
            }
        }
    }

}

<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Services\MonnifyService;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    use ApiResponseTrait;

    protected $mainAcctNumber;
    protected $mainAcctName;

    public function __construct()
    {
        $this->mainAcctNumber = config('monnify.main_account_number');
        $this->mainAcctName = config('monnify.main_account_name');
    }

    /**
    * Get admin details
    *
    * If everything is okay, you'll get a 200 OK response.
    *
    * Otherwise, the request will fail with an error, and a response listing the failed services.
    *
    * @response 200 {
    *       "status": "success",
    *       "message": "Admin Account Fetched Successfully",
    *       "data": {
    *           "accountName": "Test Account",
    *           "accountNumber": "6318939922",
    *           "accountBalance": {
    *               "availableBalance": 4999997000,
    *               "ledgerBalance": 4999997000
    *           }
    *       }
    * }

     */

    public function show() {
        $monnify = new MonnifyService();
        $account_details = $monnify->getAdminAccount();

        if ($account_details == null) {
            return $this->error('Something Went Wrong', 500);
        }
        else {
            if ($account_details['requestSuccessful'] == true) {
                $response = [
                    'accountName' => $this->mainAcctName,
                    'accountNumber' => $this->mainAcctNumber,
                    'accountBalance' => $account_details['responseBody']
                ];
            }
            else {
                $response = ucwords($account_details['responseMessage']);

                return $this->error($response,  404);
            }
        }

        return $this->success($response, 'Admin Account Fetched Successfully', 200);
    }
}

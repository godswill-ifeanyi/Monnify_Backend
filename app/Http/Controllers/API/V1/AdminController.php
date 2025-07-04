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

    public function show($account_number) {
        $monnify = new MonnifyService();
        $account_details = $monnify->getAdminAccount($account_number);

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
            }
        }

        return $this->success($response, 'Admin Account Fetched Successfully', 200);
    }
}

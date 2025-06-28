<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\MonnifyService;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CreateUserRequest;

class UserController extends Controller
{
    use ApiResponseTrait;

    /**
    *   Create a reserved bank account.
    *

    * @response 201 {
    *       "status": "success",
    *       "message": "Reserved Account Created Successfully",
    *       "data": {
    *           "name": "John Doe",
    *           "chamberName": "John Doe & Sons Chambers",
    *           "email": "john.doe@example.com",
    *           "nin": 5767676767,
    *           "accountRef": "cliApp684404f8964ec",
    *           "bankDetails": {
    *               "accountNumber": 3318057324,
    *               "accountName": "JOHN DOE & SONS CHAMBERS",
    *               "bankName": "Wema bank"
    *           }
    *       }
 * }

    */

    public function create(CreateUserRequest $request) {
        $data = $request->validated();

        $user = new User;
        $user->account_ref = 'cliApp'.uniqid();
        $user->name = $data['name'];
        $user->chamber_name = $data['chamberName'];
        $user->email = $data['email'];
        $user->nin = $data['nin'];
        $user->save();

        $monnify = new MonnifyService();

        /* $nin_verify = $monnify->verifyNIN($user->nin);
        if ($nin_verify['requestSuccessful'] == false) {
                $user->delete();

                return $this->error(ucwords($nin_verify['responseMessage']),  422);
            } */

        $account = $monnify->createReservedAccount($user);

        if ($account == null) {
            $user->delete();

            return $this->error('Something Went Wrong',  403);
        }
        else {
            if ($account['requestSuccessful'] == false) {
                $user->delete();

                return $this->error(ucwords($account['responseMessage']),  422);
            }
        }

        return $this->success(new UserResource($user), 'Reserved Account Created Successfully', 201);
    }

    public function show($account_ref) {
        $user = User::where('account_ref', $account_ref)->first();

        return $this->success(new UserResource($user), 'Reserved Account Fetched Successfully', 200);
    }
}

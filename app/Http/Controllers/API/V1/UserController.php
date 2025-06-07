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
        $account = $monnify->createReservedAccount($user);

        return $this->success(new UserResource($user), 'Reserved Account Created Successfully', 201);
    }
}

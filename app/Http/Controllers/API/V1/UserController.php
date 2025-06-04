<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\MonnifyService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CreateUserRequest;

class UserController extends Controller
{
    public function create(CreateUserRequest $request) {
        $data = $request->validated();

        $user = new User;
        $user->account_ref = 'cliApp'.uniqid();
        $user->name = $data['name'];
        $user->chamber_name = $data['chamber_name'];
        $user->email = $data['email'];
        $user->nin = $data['nin'];
        $user->save();

        $monnify = new MonnifyService();
        $account = $monnify->createReservedAccount($user);

        return new UserResource($user);
    }
}

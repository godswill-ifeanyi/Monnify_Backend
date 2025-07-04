<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'chamberName' => $this->chamber_name,
            'email' => $this->email,
            'nin' => $this->nin,
            'bankDetails' => [
                'accountRef' => $this->account_ref,
                'accountNumber' => $this->virtualAccount->account_number,
                'accountName' => $this->virtualAccount->account_name,
                'bankName' => $this->virtualAccount->bank_name,
                'accountBalance' => $this->virtualAccount->balance
            ],
        ];
    }
}

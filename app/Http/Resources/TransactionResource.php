<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $details = [];
        
        if ($this->type == "debit") {
            $details = [
                'totalFee' => $this->disburseDetail->total_fee,
                'receiverAccountName' => $this->disburseDetail->destination_account_name,
                'receiverAccountNumber' => $this->disburseDetail->destination_account_number,
                'receiverBankName' => $this->disburseDetail->destination_bank_name,
            ];
        }
        else if ($this->type == 'credit') {
            $details = [
                'receiverAccountName' => $this->depositDetail->sender_account_name,
                'receiverAccountNumber' => $this->depositDetail->sender_account_number,
                'receiverBankName' => $this->depositDetail->sender_bank_code,
            ];
        }

        return [
            'accountRef' => $this->user->account_ref,
            'accountName' => $this->virtualAccount->account_name,
            'accountNumber' => $this->virtualAccount->account_number,
            'bankName' => $this->virtualAccount->bank_name,
            'type' => $this->type,
            'details' => $details
        ];
    }
}

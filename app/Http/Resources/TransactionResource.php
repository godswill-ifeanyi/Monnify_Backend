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
                'type' => $this->type,
                'amount' => $this->amount,
                'totalFee' => $this->disburseDetail->total_fee,
                'narration' => $this->narration,
                'reference' => $this->reference,
                'isCompleted' => $this->is_completed,
                'receiverAccountNumber' => $this->disburseDetail->destination_account_number,
                'receiverBankName' => $this->disburseDetail->destination_bank_name,
                'receiverBankCode' => $this->disburseDetail->destination_bank_code,
            ];
        }
        else if ($this->type == 'credit') {
            $details = [
                'type' => $this->type,
                'amount' => $this->amount,
                'narration' => $this->narration,
                'reference' => $this->reference,
                'isCompleted' => $this->is_completed,
                'senderAccountName' => $this->depositDetail->sender_account_name,
                'senderAccountNumber' => $this->depositDetail->sender_account_number,
                'senderBankCode' => $this->depositDetail->sender_bank_code,
            ];
        }

        return [
            'accountRef' => $this->user->account_ref,
            'accountName' => $this->virtualAccount->account_name,
            'accountNumber' => $this->virtualAccount->account_number,
            'bankName' => $this->virtualAccount->bank_name,
            'transactionDetails' => $details,
            'createdAt' => $this->created_at
        ];
    }
}

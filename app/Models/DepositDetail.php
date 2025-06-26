<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class DepositDetail extends Model
{
    protected $fillable = [
        'transaction_id', 'sender_bank_code', 'sender_account_number', 'sender_account_name'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class DisburseDetail extends Model
{
    protected $fillable = [
        'transaction_id','total_fee', 'destination_bank_name', 'destination_account_number', 'destination_bank_code'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

<?php

namespace App\Models;

use App\Models\User;
use App\Models\DepositDetail;
use App\Models\DisburseDetail;
use App\Models\VirtualAccount;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'virtual_account_id', 'type', 'amount', 'reference', 'narration', 'is_completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function virtualAccount()
    {
        return $this->belongsTo(VirtualAccount::class);
    }

    public function disburseDetail()
    {
        return $this->hasOne(DisburseDetail::class);
    }

    public function depositDetail()
    {
        return $this->hasOne(DepositDetail::class);
    }
}

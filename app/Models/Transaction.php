<?php

namespace App\Models;

use App\Models\User;
use App\Models\DisburseDetail;
use App\Models\VirtualAccount;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'virtual_account_id', 'type', 'amount', 'reference', 'narration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function virtutalAccount()
    {
        return $this->belongsTo(VirtualAccount::class);
    }

    public function disburseDetail()
    {
        return $this->hasOne(DisburseDetail::class);
    }
}

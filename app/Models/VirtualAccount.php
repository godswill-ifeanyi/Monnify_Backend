<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    protected $fillable = [
        'user_id', 'account_number', 'account_name', 'bank_name', 'balance'
    ];   

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}

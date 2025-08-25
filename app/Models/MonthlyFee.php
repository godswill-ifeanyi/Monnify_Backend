<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyFee extends Model
{
    protected $table = 'monthly_fee';

    public $timestamps = true;
    
    protected $fillable = [
        'amount',
    ];
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Transaction;
use App\Models\VirtualAccount;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_ref',
        'name',
        'chamber_name',
        'email',
        'nin',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime'
        ];
    }

    public function virtualAccount()
    {
        return $this->hasOne(VirtualAccount::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

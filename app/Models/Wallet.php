<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial',
        'status',
        'balance',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class, ['sender_wallet', 'receiver_wallet']);
    }
}

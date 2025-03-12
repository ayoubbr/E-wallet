<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial',
        'status',
        'amount',
        'sender_wallet',
        'receiver_wallet',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class);
    }

    public function sender_wallet()
    {
        return $this->belongsTo(Wallet::class, 'sender_wallet');
    }

    public function receiver_wallet()
    {
        return $this->belongsTo(Wallet::class, 'receiver_wallet');
    }
}

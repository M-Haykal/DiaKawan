<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_type',
        'transaction_status',
        'fraud_status',
        'payload',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
    'transaction_number',
    'midtrans_order_id',
    'subtotal',
    'discount',
    'tax',
    'fee',
    'grand_total',
    'payment',
    'change',
    'payment_method',
    'payment_status',
    'cashier',
    'customer',
];

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'subtotal',
        'discount',
        'tax',
        'fee',
        'grand_total',
        'payment',
        'change',
        'payment_method',
        'cashier',
        'customer',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
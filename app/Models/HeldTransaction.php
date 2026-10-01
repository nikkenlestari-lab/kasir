<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeldTransaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'cart',
        'subtotal',
        'discount',
        'tax',
        'fee',
        'grand_total',
        'cashier',
    ];

    protected $casts = [
        'cart' => 'array',
    ];
}
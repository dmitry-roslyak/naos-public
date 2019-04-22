<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_state', 'payment_state', 'visa_an', 'visa_rrn'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProductRating extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'rating'
    ];
}

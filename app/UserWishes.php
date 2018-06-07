<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWishes extends Model
{
    protected $hidden = [
        // 'user_id',
        'product_id'
    ];
    public $timestamps = false;

    public function prod()
    {
        return $this->hasOne('App\Product','id', 'product_id')->select(['id','price','available','img_src','name']);
    }
    public function crnc()
    {
        return $this->hasMany('App\Currency', 'date', 'date')->select(['date','rate','name']);
    }
}

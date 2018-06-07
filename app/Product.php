<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function specs()
    {
        return $this->hasMany('App\Spec','prod_id','id');
    }
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
    public function discount()
    {
        return $this->hasOne('App\Discount','id', 'discount_id' );
    }
    public function wish()
    {
        //$user_id = \Auth::id();
        return $this->hasOne('App\UserWishes','product_id', 'id' );//->where('user_id',$user_id);
    }
    public function wishes()
    {
        return $this->hasMany('App\UserWishes','product_id', 'id' );
    }
    public function ctg()
    {
        return $this->hasOne('App\Category','id', 'category_id' );
    }
}
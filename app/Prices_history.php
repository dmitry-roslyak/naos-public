<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prices_history extends Model
{
    public $timestamps = false;
    public function currency()
    {
        return $this->hasOne('App\Currency','date','date')->select(['date','rate']);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilterValue extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'filter_id',
        'priority',
        'popularity',
        'count',
        'value'
    ];
    public function prod_ids()
    {
        return $this->hasMany('App\FilterValueProduct','filter_value_id','id');
    }
}

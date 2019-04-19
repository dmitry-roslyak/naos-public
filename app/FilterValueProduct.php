<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilterValueProduct extends Model
{
    protected $primaryKey = null;
    public $timestamps = false;
    protected $fillable = ['product_id','filter_value_id'];
}

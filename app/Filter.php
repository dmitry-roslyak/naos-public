<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    public $timestamps = false;
    protected $fillable = ['category_id','name','desc'];
    public function values()
    {
        return $this->hasMany('App\FilterValue','filter_id','id')->with('prod_ids');
    }
}

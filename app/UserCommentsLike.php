<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCommentsLike extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'is_liked'
    ];
}

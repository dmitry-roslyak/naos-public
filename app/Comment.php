<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function user()
    {
        return $this->hasOne(
            'App\User','id', 'user_id' )->select(['id','name']);
    }
    public function vote()
    {
        return $this->hasOne('App\UserCommentsLike','comment_id','id');
    }
}

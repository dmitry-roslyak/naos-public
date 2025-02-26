<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt; 

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','language','currency', 'fname' , 'lname', 'tel'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','id', 'api_token'
    ];
    public function all_wishes()
    {
        return $this->hasMany('App\UserWishes', 'user_id', 'id');
    }
    public function wishes()
    {
        return $this->hasMany('App\UserWishes', 'user_id', 'id')->with('prod');
    }
    public function getPanAttribute($value)
    {
        if(!$value) return '';
        return Crypt::decryptString($value);
    }
    public function isAdmin()
    {
        return $this->bstate == 255;
        //->where('bstate', 255)->get();;
        // return User::where('bstate', 255)->get();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login_id', 'password', 'enabled',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     *
     */
    use SoftDeletes;
    protected $hidden = [
        'password', 'remember_token',
    ];

    public  function  cities(){
        return $this->belongsToMany('App\City','city_users');
    }

    public  function  schools(){
        return $this->belongsToMany('App\School','school_users');
    }

    public  function  classes(){
        return $this->belongsToMany('App\Klass','challenge_users','user_id','class_id');
    }
}

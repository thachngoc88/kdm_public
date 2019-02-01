<?php

namespace App;

class CityUser extends SoftDeletableModel
{
    //
    public $table = "city_users";

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function city(){
        return $this->belongsTo('App\City');
    }
}

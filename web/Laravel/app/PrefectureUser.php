<?php

namespace App;

class PrefectureUser extends SoftDeletableModel
{
    public $table = "prefecture_users";
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function prefecture(){
        return $this->belongsTo('App\Prefecture');
    }
}

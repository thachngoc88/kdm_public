<?php

namespace App;

class SchoolUser extends SoftDeletableModel
{
    //
    public $table = "school_users";

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function school(){
        return $this->belongsTo('App\School');
    }
}

<?php

namespace App;

class Klass extends SoftDeletableModel
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    public $table = "classes";
    public function school(){
        return $this->belongsTo('App\School');
    }

    public function grade(){
        return $this->belongsTo('App\Grade');
    }

    public function users()
    {
        return $this->belongsToMany('App\User','challenge_users','class_id','user_id');
    }

    public function challengeUsers(){
        return $this->belongsToMany('App\ChallengeUser')->withTimestamps();
    }
}

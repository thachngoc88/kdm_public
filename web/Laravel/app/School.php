<?php

namespace App;

class School extends SoftDeletableModel
{

    public $table = "schools";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($scools) {
            foreach ($scools->classes()->get() as $cl) {
                $cl->delete();
            }
            foreach ($scools->schoolUsers()->get() as $scu) {
                $scu->delete();
            }
        });
    }

    public function city(){
        return $this->belongsTo('App\City');
    }

    public function classes(){
        return $this->hasMany('App\Klass');
    }

    public function schoolUsers(){
        return $this->hasMany('App\SchoolUser');
    }

    public function users()
    {
        return $this->belongsToMany('App\User','school_users');
    }
}

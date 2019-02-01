<?php

namespace App;


class City extends SoftDeletableModel
{
    //
    public $table = "cities";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($cities) {
            foreach ($cities->schools()->get() as $sc) {
                $sc->delete();
            }
            foreach ($cities->cityUsers()->get() as $cu) {
                $cu->delete();
            }
        });
    }

    public function prefecture(){
        return $this->belongsTo('App\Curriculum');
    }

    public function schools(){
        return $this->hasMany('App\School');
    }

    public function cityUsers(){
        return $this->hasMany('App\CityUser');
    }
}

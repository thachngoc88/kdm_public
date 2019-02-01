<?php

namespace App;

class Prefecture extends SoftDeletableModel
{
    //
    public $table = "prefectures";

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($prefectures) {
            foreach ($prefectures->cities()->get() as $ct) {
                $ct->delete();
            }
        });
    }

    public function cities(){

        return $this->hasMany('App\City');
    }

}

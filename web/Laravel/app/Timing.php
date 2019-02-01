<?php

namespace App;

class Timing extends SoftDeletableModel
{
    //
    protected $table ="timings";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($timings) {
            foreach ($timings->conditions()->get() as $cd) {
                $cd->delete();
            }
        });
    }

    public function  conditions(){
        return $this->hasMany('App\Condition');
    }
    public  function  subject(){
        return $this->belongsTo('App\Subject');
    }
}

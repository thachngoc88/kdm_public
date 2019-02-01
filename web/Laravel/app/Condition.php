<?php

namespace App;

class Condition extends SoftDeletableModel
{
    //
    protected $table = "conditions";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($conditionss) {
            foreach ($conditionss->messages()->get() as $ms) {
                $ms->delete();
            }
        });
    }
    public function messages(){
        return $this->hasMany('App\Message');
    }
    public function timing(){
        return $this->belongsTo('App\Timing');
}
}

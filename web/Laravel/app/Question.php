<?php

namespace App;

class Question extends SoftDeletableModel
{
    //
    public $table = "questions";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($questions) {
            foreach ($questions->records()->get() as $rc) {
                $rc->delete();
            }
        });
    }

    public function workbook() {
        return $this->belongsTo('App\Workbook');
    }

    public function answer() {
        return $this->belongsTo('App\Answer');
    }
    public function records(){
        return $this->hasMany('App\Record','question_id','id');
    }
}

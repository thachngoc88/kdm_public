<?php

namespace App;

class Grade extends SoftDeletableModel
{

    public $table = "grades";
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($grades) {
            foreach ($grades->curriculums()->get() as $cu) {
                $cu->delete();
            }
            foreach ($grades->classes()->get() as $cl) {
                $cl->delete();
            }
        });
    }
    public function curriculums(){
        return $this->hasMany('App\Curriculum');
    }
    public function classes(){
        return $this->hasMany('App\Klass');
    }
    public function subjects(){
        return $this->belongsToMany('App\Subject','curriculums');
    }


}

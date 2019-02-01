<?php

namespace App;

class Subject extends SoftDeletableModel
{
    //
    public $table = "subjects";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($subjects) {
            foreach ($subjects->curriculums()->get() as $cu) {
                $cu->delete();
            }
        });
    }


    public function curriculums(){
        return $this->hasMany('App\Curriculum');
    }
}

<?php

namespace App;

class Answer extends SoftDeletableModel
{
    //
    public $table = "answers";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($answers) {
            $answers->question()->delete();
        });
    }
    public function question() {
        return $this->hasOne('App\Question');
    }
}

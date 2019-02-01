<?php

namespace App;

class Supplement extends SoftDeletableModel
{
    //
    public $table = "supplements";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($supplements) {
            foreach ($supplements->challenge_questions_supplenments()->get() as $cqs) {
                $cqs->delete();
            }
        });
    }

    public function workbook() {
        return $this->belongsTo('App\Workbook');
    }
    public function challenge_questions_supplenments() {
        return $this->hasMany('App\ChallengeQuestionSupplement');
    }
}

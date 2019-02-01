<?php

namespace App;

class Challenge extends SoftDeletableModel
{
    //
    public $table = "challenges";

    public function workbook() {
        return $this->belongsTo('App\Workbook');
    }
    public function challenge_questions_supplements() {
        return $this->hasMany('App\ChallengeQuestionSupplement');
    }
}

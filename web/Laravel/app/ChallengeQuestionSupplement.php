<?php

namespace App;

class ChallengeQuestionSupplement extends SoftDeletableModel
{
    //
    protected $table = "challenge_questions_supplements";

    public function challenge(){
        return $this->belongsTo('App\Challenge');
    }

    public function question(){
        return $this->belongsTo('App\Question');
    }

    public function supplement(){
        return $this->belongsTo('App\Supplement');
    }
}

<?php

namespace App;

class FirstRecord extends SoftDeletableModel
{
    //
    public $table = "first_records";
    protected $fillable = ['challenge_user_id', 'question_id', 'record'];

    public function question() {
        return $this->belongsTo('App\Question');
    }

    public function challengeUser() {
        return $this->belongsTo('App\ChallengeUser');
    }
}

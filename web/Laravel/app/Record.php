<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends SoftDeletableModel
{
    //
    public $table = "records";

    protected $fillable = ['challenge_user_id', 'question_id', 'record'];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function question() {
        return $this->belongsTo('App\Question');
    }

    public function challengeUser() {
        return $this->belongsTo('App\ChallengeUser');
    }
}

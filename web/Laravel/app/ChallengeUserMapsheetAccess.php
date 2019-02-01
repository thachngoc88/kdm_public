<?php

namespace App;
class ChallengeUserMapsheetAccess extends SoftDeletableModel
{
    //
    public $table = "challenge_user_mapsheet_accesses";
    protected $fillable = [
        'challenge_user_id', 'curriculum_id',
    ];

    public function curriculum(){
        return $this->belongsTo('App\Curriculum');
    }

    public function challenge_user(){
        return $this->belongsTo('App\ChallengeUser');
    }
}

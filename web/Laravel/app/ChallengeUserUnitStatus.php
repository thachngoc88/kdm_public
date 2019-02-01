<?php

namespace App;

class ChallengeUserUnitStatus extends SoftDeletableModel
{
    //
    protected $table = "challenge_user_unit_statuses";

    protected $fillable = [
        'challenge_user_id', 'unit_id',
    ];

    public function unit(){
        return $this->belongsTo('App\Unit');
    }

    public function challenge_user(){
        return $this->belongsTo('App\ChallengeUser');
    }
}

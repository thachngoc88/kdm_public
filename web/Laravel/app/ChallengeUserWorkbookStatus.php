<?php

namespace App;

class ChallengeUserWorkbookStatus extends SoftDeletableModel
{
    //
    protected $table = "challenge_user_workbook_statuses";

    protected $fillable = [
        'challenge_user_id', 'workbook_id',
    ];

    public function workbook(){
        return $this->belongsTo('App\Workbook');
    }

    public function challenge_user(){
        return $this->belongsTo('App\ChallengeUser');
    }
}

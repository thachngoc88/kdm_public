<?php

namespace App;

class ChallengeUserWorkbookUpdateCount extends SoftDeletableModel
{
    //
    protected $table  = "challenge_user_workbook_update_counts";
    protected $fillable = [
        'challenge_user_id', 'workbook_id','count'
    ];

    public function workbook(){
        return $this->belongsTo('App\Workbook');
    }

    public function challenge_user(){
        return $this->belongsTo('App\ChallengeUser');
    }
}

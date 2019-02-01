<?php

namespace App;

class Mark extends SoftDeletableModel
{
    //
    protected $table = "marks";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'challenge_user_id', 'workbook_id',
    ];

    public function challenge_user(){
        return $this->belongsTo('App\ChallengeUser');
    }

    public function workbook(){
        return $this->belongsTo('App\Workbook');
    }
    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }
}

<?php

namespace App;

class ChallengeUser extends SoftDeletableModel
{
    //
    public $table = "challenge_users";

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($challengeusers) {
            foreach ($challengeusers->records()->get() as $record) {
                $record->delete();
            }
        });
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function klass(){
        return $this->belongsTo('App\Klass', 'class_id');
    }

    public function records(){
        return $this->hasMany('App\Record');
    }
}

<?php

namespace App;

class Message extends SoftDeletableModel
{
    //
    protected $table = "messages";

    public function condition(){
        return $this->belongsTo('App\Condition');
    }
}

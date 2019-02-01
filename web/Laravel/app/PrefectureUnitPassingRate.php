<?php

namespace App;

class PrefectureUnitPassingRate extends SoftDeletableModel
{
    //
    protected $table = "prefecture_unit_passing_rates";
    protected $fillable = [
        'prefecture_id', 'unit_id',
    ];

    public function prefecture(){
        return $this->belongsTo('App\Prefecture');
    }
    public function unit(){
        return $this->belongsTo('App\Unit');
    }
    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }
}

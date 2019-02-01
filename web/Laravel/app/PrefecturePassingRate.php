<?php

namespace App;

class PrefecturePassingRate extends SoftDeletableModel
{
    //
    protected $table = "prefecture_passing_rates";
    protected $fillable = [
        'prefecture_id', 'workbook_id',
    ];

    public function prefectue(){
        return $this->belongsTo('App\Prefecture');
    }
    public function workbook(){
        return $this->belongsTo('App\Workbook');
    }
    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }

}

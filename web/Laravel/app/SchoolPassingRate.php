<?php

namespace App;

class SchoolPassingRate extends SoftDeletableModel
{
    //
    protected $table = "school_passing_rates";

    protected $fillable = [
        'school_id', 'workbook_id',
    ];

    public function school(){
        return $this->belongsTo('App\School');
    }

    public function workbook(){
        return $this->belongsTo('App\Workbook');
    }

    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }
}

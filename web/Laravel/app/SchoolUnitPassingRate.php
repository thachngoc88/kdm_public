<?php

namespace App;

class SchoolUnitPassingRate extends SoftDeletableModel
{
    //
    protected $table = "school_unit_passing_rates";

    protected $fillable = [
        'school_id', 'unit_id',
    ];

    public function school(){
        return $this->belongsTo('App\School');
    }
    public function unit(){
        return $this->belongsTo('App\Unit');
    }
    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }
}

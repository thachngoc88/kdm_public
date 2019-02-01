<?php

namespace App;

class SchoolCurriculumPassingRate extends SoftDeletableModel
{
    //
    protected $table = "school_curriculum_passing_rates";

    protected $fillable = [
        'school_id', 'curriculum_id',
    ];

    public function school(){
        return $this->belongsTo('App\School');
    }
    public function curriculum(){
        return $this->belongsTo('App\Curriculum');
    }
    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }
}

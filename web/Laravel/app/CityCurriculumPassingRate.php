<?php

namespace App;

class CityCurriculumPassingRate extends SoftDeletableModel
{
    //
    protected $table = "city_curriculum_passing_rates";
    protected $fillable = [
        'city_id', 'curriculum_id',
    ];

    public function city(){
        return $this->belongsTo('App\City');
    }
    public function curriculum(){
        return $this->belongsTo('App\Curriculum');
    }
    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }
}

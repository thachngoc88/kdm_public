<?php

namespace App;

class PrefectureCurriculumPassingRate extends SoftDeletableModel
{
    //
    protected $table = "prefecture_curriculum_passing_rates";


    protected $fillable = [
        'prefecture_id', 'curriculum_id',
    ];

    public function prefecture(){
        return $this->belongsTo('App\Prefecture');
    }
    public function curriculum(){
        return $this->belongsTo('App\Curriculum');
    }
    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }

}

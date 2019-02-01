<?php

namespace App;

class ClassCurriculumPassingRate extends SoftDeletableModel
{
    //
    protected $table = "class_curriculum_passing_rates";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class_id', 'curriculum_id',
    ];

    public function klass(){
        return $this->belongsTo('App\Klass');
    }

    public function curriculum(){
        return $this->belongsTo('App\Curriculum');
    }

    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }
}

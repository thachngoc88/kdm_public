<?php

namespace App;

class ClassUnitPassingRate extends SoftDeletableModel
{
    //
    protected $table = "class_unit_passing_rates";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class_id', 'unit_id',
    ];

    public function klass(){
        return $this->belongsTo('App\Klass');
    }

    public function unit(){
        return $this->belongsTo('App\Unit');
    }

    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }
}

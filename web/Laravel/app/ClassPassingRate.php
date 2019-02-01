<?php

namespace App;

class ClassPassingRate extends SoftDeletableModel
{
    //
    protected $table = "class_passing_rates";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class_id', 'workbook_id',
    ];

    public function klass(){
        return $this->belongsTo('App\Klass','class_id','id');
    }

    public function workbook(){
        return $this->belongsTo('App\Workbook');
    }
    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }
}

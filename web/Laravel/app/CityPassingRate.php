<?php

namespace App;

class CityPassingRate extends SoftDeletableModel
{
    //
    protected $table = "city_passing_rates";

    protected $fillable = [
        'city_id', 'workbook_id',
    ];

    public function city(){
        return $this->belongsTo('App\City');
    }
    public function workbook(){
        return $this->belongsTo('App\Workbook');
    }
    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }
}

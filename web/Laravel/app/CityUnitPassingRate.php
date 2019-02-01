<?php

namespace App;

class CityUnitPassingRate extends SoftDeletableModel
{
    //
    protected $table = "city_unit_passing_rates";

    protected $fillable = [
        'city_id', 'unit_id',
    ];

    public function city(){
        return $this->belongsTo('App\City');
    }
    public function unit(){
        return $this->belongsTo('App\Unit');
    }
    public function marking_log(){
        return $this->belongsTo('App\MarkingLog');
    }

}

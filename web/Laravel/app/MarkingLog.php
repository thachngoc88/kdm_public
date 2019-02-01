<?php

namespace App;

class MarkingLog extends SoftDeletableModel
{
    //
    protected $table = "marking_logs";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($markinglogs) {
            foreach ($markinglogs->marks()->get() as $mark) {
                $mark->delete();
            }
            foreach ($markinglogs->class_passing_rates()->get() as $cpr) {
                $cpr->delete();
            }
            foreach ($markinglogs->class_unit_passing_rates()->get() as $cupr) {
                $cupr->delete();
            }
            foreach ($markinglogs->class_curriculum_passing_rates()->get() as $ccpr) {
                $ccpr->delete();
            }
        });
    }

    public function marks(){
        return $this->hasMany('App\Mark');
    }

    public function class_passing_rates(){
        return $this->hasMany('App\ClassPassingRate');
    }
    public function class_unit_passing_rates(){
        return $this->hasMany('App\ClassUnitPassingRate');
    }
    public function class_curriculum_passing_rates(){
        return $this->hasMany('App\ClassCurriculumPassingRate');
    }
}

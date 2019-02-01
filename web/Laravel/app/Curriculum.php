<?php

namespace App;

class Curriculum extends SoftDeletableModel
{
    protected $table = "curriculums";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($curriculums) {
            foreach ($curriculums->units()->get() as $un) {
                $un->delete();
            }
            foreach ($curriculums->timings()->get() as $ti) {
                $ti->delete();
            }
        });
    }

    public function grade() {
        return $this->belongsTo('App\Grade');
    }

    public function subject() {
        return $this->belongsTo('App\Subject');
    }

    public function units() {
        return $this->hasMany('App\Unit')->orderBy('number');
    }

    public function timings() {
        return $this->hasMany('App\Timing');
    }

    /**
     *
     * @return string
     */
    public function getDisplayTitleAttribute()
    {
        return $this->grade->number . "年 " . $this->subject->name;
    }
}

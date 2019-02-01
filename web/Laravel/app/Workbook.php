<?php

namespace App;

class Workbook extends SoftDeletableModel
{
    //
    public $table = "workbooks";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($workbooks) {
            foreach ($workbooks->questions()->get() as $qs) {
                $qs->delete();
            }
            $workbooks->challenge()->delete();
            $workbooks->supplement()->delete();
        });
    }

    public function unit() {
        return $this->belongsTo('App\Unit');
    }

    public function challenge() {
        return $this->hasOne('App\Challenge');
    }

    public function supplement() {
        return $this->hasOne('App\Supplement');
    }

    public function questions() {
        return $this->hasMany('App\Question');
    }

}

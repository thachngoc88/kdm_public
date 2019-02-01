<?php

namespace App;

class Unit extends SoftDeletableModel
{
    public $table = "units";

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($units) {
            foreach ($units->workbooks()->get() as $wb) {
                $wb->delete();
            }
        });
    }

    public function curriculum() {
        return $this->belongsTo('App\Curriculum');
    }

    public function workbooks() {
        return $this->hasMany('App\Workbook')->orderBy('number');
    }
}

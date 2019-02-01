<?php

namespace App;

class ExistingPdf extends SoftDeletableModel
{
    //
    protected $table = "existing_pdfs";
    protected $fillable = ['type', 'existing', 'workbook_id'];
    public function workbook() {
        return $this->belongsTo('App\Workbook');
    }
}

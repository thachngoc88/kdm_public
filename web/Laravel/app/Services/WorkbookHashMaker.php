<?php

namespace App\Services;

use App\Curriculum;

class WorkbookHashMaker
{
    public static function make($curriculumId){
        $workbookHashes = [];
        foreach(Curriculum::find($curriculumId)->units as $unit){
            $un = $unit->number;
            foreach($unit->workbooks as $workbook){
                $wn = $workbook->number;
                $workbookKey = $wn === 0 ? "内容{$un}" : "補{$un}-{$wn}";
                $workbookHashes[$workbookKey] = [
                    'title' => $unit->name,
                    'content' => $workbook->title
                ];
            }
        }
        return json_encode($workbookHashes);
    }
}

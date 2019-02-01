<?php

namespace App\Services;


use App\ExistingPdf;

class Utils
{
    const HYPHEN_SYMBOL = "-";


    public static function checkExistBothFileDownloadInWorkbook($workbook, $fromDb = false){
        if($fromDb){
            return self::checkExistFileDownloadInWorkbookFromDb('q',$workbook) && self::checkExistFileDownloadInWorkbookFromDb('a',$workbook);
        }else{
            return self::checkExistFileDownloadInWorkbook('q',$workbook) && self::checkExistFileDownloadInWorkbook('a',$workbook);
        }
    }

    public static function checkExistFileDownloadInWorkbook($type, $workbook){
        $path = Self::getPathName();
        $fileName = Self::getFileName($workbook, $type);
        return file_exists($path.$fileName);
    }

    public static function checkExistFileDownloadInWorkbookFromDb($type, $workbook){
        $existPdf = ExistingPdf::where(['type' => $type, 'workbook_id' => $workbook->id, 'existing'=>1])->first();
        if(isset($existPdf))
            return true;
        else
            return false;
    }

    public static function getPathName(){
        return storage_path("app/public/");
    }

    public static function getFileName($workbook, $type){
        $unit = $workbook->unit;
        $grade = $workbook->unit->curriculum->grade;
        $subject = $workbook->unit->curriculum->subject;

        $grade_number = $grade->number;
        $subject_char = $subject->name == '算数' ? 'S' : 'K';
        $unit_number = sprintf("%02d", $unit->number);
        $workbook_number = sprintf("%02d", $workbook->number);

        $fileNameItem = [
            'grade_group' => 'S'.$grade_number,
            'subject_unit_group' => $subject_char.$unit_number,
            'workbook_id_group' => $workbook_number,
            'question_answer_group' => $type == 'q' ? 'Q' : 'A'
        ];
        return implode(Self::HYPHEN_SYMBOL,$fileNameItem).'.pdf';
    }
}

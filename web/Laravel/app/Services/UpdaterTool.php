<?php

namespace App\Services;

use App\ChallengeUser;
use App\Curriculum;
use App\DataTables\AggregationDataTable;
use App\Klass;
use App\Unit;
use App\Workbook;

class UpdaterTool
{
//    const NYS = '-';
//    const FAIL = '△';
//    const PASS = '◯';
//    const PASS_CHL = '◎';{
    const CIRCLE = '●';
    const DELTA = '▲';
    const HYPHEN = '-';

    const NYS = '<span>' . UpdaterTool::HYPHEN . '</span>';
    const FAIL = '<span style="color:#EB3223">' . UpdaterTool::DELTA . '</span>';
    const PASS = '<span style="color:#408F4D">' . UpdaterTool::CIRCLE . '</span>';
    const PASS_CHL = '<span ischallenge style="color:#408F4D">' . UpdaterTool::CIRCLE . '</span>';
    const PASS_SCORE = 80;


    public static function getClassesOfGradeNumber($gradeNumber){
        return Klass::whereHas('grade', function ($q) use ($gradeNumber){
            $q->where('number', '=', $gradeNumber);
        })->get();
    }

    public static function getChallengeUsersOfGradeNumber($gradeNumber){
        return ChallengeUser::whereHas('klass.grade', function ($q) use ($gradeNumber){
            $q->where('number', '=', $gradeNumber);
        })->get();
    }

    public static function getWorkbooksOfGradeNumber($gradeNumber, $wheres = []){
        return Workbook::whereHas('unit.curriculum.grade', function($query) use ($gradeNumber, $wheres){
            $query->where('number', '=', $gradeNumber);
            foreach($wheres as $column => $value){
                $query->where($column, '=', $value);
            }
        })->get();
    }

    public static function getUnitsOfGradeNumber($gradeNumber){
        return Unit::whereHas('curriculum.grade', function($query) use ($gradeNumber){
            $query->where('number', '=', $gradeNumber);
        })->get();
    }


    public static function getCurriculumsOfGradeNumber($gradeNumber){
        return Curriculum::whereHas('grade', function($query) use ($gradeNumber){
            $query->where('number', '=', $gradeNumber);
        })->get();
    }

    public static function isBelowPrefectureMode($mode){
        return self::isCityMode($mode) || self::isSchoolMode($mode) || self::isClassMode($mode);
    }

    public static function isBelowCityMode($mode){
        return self::isSchoolMode($mode) || self::isClassMode($mode);
    }

    public static function isBelowSchoolMode($mode){
        return self::isClassMode($mode);
    }

    public static function isPrefectureMode($mode){
        return $mode === AggregationDataTable::MODE_PREFECTURES;
    }

    public static function isCityMode($mode){
        return $mode === AggregationDataTable::MODE_CITIES;
    }

    public static function isSchoolMode($mode){
        return $mode === AggregationDataTable::MODE_SCHOOLS;
    }

    public static function isClassMode($mode){
        return $mode === AggregationDataTable::MODE_CLASSES;
    }

    public static function createPercentSpan($number){
        $span = '<span';
        $spanInner = '';
        $n = $number * 100;
        $n = round($n, 2);
        if($n >= 100){
            $spanInner = ' style="color:#408F4D"';
        }elseif($n >= 80){
            $spanInner = ' style="color:#EB3223"';
        }
        $span = "{$span}{$spanInner}>{$n}%</span>";
        return $span;
    }
}

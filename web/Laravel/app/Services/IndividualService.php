<?php

namespace App\Services;

use App\CityCurriculumPassingRate;
use App\CityPassingRate;
use App\ClassCurriculumPassingRate;
use App\ClassPassingRate;
use App\Curriculum;
use App\PrefectureCurriculumPassingRate;
use App\PrefecturePassingRate;
use App\SchoolCurriculumPassingRate;
use App\SchoolPassingRate;


class IndividualService
{

    private static $wb_id_array;

    public static function getFooterData($prep_id, $city_id, $school_id, $class_id, $curri_id,$typeExport)
    {
        $dataFooter = array();
        $curriculum = Curriculum::find($curri_id);
        Self::$wb_id_array = self::getAllWorkbookIdOfCurriculum($curriculum);

        if(isset($class_id) && is_numeric($class_id)){
            $dataFooter[] = Self::getDataFooterOfClass($class_id,$curriculum,$typeExport);
        }
        if(isset($school_id) && is_numeric($school_id)){
            $dataFooter[] = Self::getDataFooterOfSchool($school_id,$curriculum,$typeExport);
        }
        if(isset($city_id) && is_numeric($city_id)){
            $dataFooter[] = Self::getDataFooterOfCity($city_id,$curriculum,$typeExport);
        }
        $dataFooter[] = Self::getDataFooterOfPrefecture($prep_id,$curriculum,$typeExport);
        return $dataFooter;
    }

    public static function getAllWorkbookIdOfCurriculum($curriculum){
        $wb_id_array = [];
        foreach($curriculum->units as $unit){
            foreach($unit->workbooks as $wb){
                $wb_id_array[]= $wb->id;
            }
        }
        return $wb_id_array;
    }

    private static function getDataFooterOfPrefecture($prep_id, $curriculum,$typeExport){
        $passing_rate_list = Self::getPrefecturePassingRate($prep_id);
        $sum = Self::getPrefectureCurriculumPassingRate($curriculum->id, $prep_id);
        return Self::getDataFooterByPassRate($passing_rate_list, $sum, $curriculum,"県平均",$typeExport);
    }

    private static function getDataFooterOfCity($city_id, $curriculum,$typeExport){
        $passing_rate_list = Self::getCityPassingRate($city_id);
        $sum = Self::getCityCurriculumPassingRate($curriculum->id, $city_id);
        return Self::getDataFooterByPassRate($passing_rate_list, $sum, $curriculum,"市町村平均",$typeExport);
    }

    private static function getDataFooterOfSchool($school_id, $curriculum,$typeExport){
        $passing_rate_list = Self::getSchoolPassingRate($school_id);
        $sum = Self::getSchoolCurriculumPassingRate($curriculum->id, $school_id);
        return Self::getDataFooterByPassRate($passing_rate_list, $sum, $curriculum,"学校平均",$typeExport);
    }

    private static function getDataFooterOfClass($class_id, $curriculum,$typeExport){
        $passing_rate_list = Self::getClassPassingRate($class_id);
        $sum = Self::getClassCurriculumPassingRate($curriculum->id, $class_id);
        return Self::getDataFooterByPassRate($passing_rate_list, $sum, $curriculum,"クラス平均",$typeExport);
    }

    private static function getDataFooterByPassRate($passing_rate_list, $sum, $curriculum, $row_name,$typeExport){
        $rowFooterData = ["Name" => $row_name];
        foreach($curriculum->units as $unit){
            foreach($unit->workbooks as $wb){
                $un = $unit->number;
                $wn = $wb->number;
                $wi = $wb->id;
                $colName = "u_{$un}_w{$wn}";
                $colValue = "0";
                foreach ($passing_rate_list as $cpr){
                    if($cpr->workbook_id == $wi){
                        $passing_rate = $cpr->passing_rate;
                        if($typeExport ==  true){
                            $colValue = ($passing_rate === 0 ? 0 : sprintf('%.2f', $passing_rate * 100)) . "%";
                        }else{
                            $colValue = UpdaterTool::createPercentSpan($passing_rate);
                        }
                        break;
                    }
                }
                $rowFooterData[$colName]=$colValue;
            }
        }
        $rowFooterData['total_pass'] = "-";
        $rowFooterData['count_f'] = "-";
        $rowFooterData['count_nys'] = "-";

        if($sum){
            if($typeExport ==  true){
                $rowFooterData['rate_impr']= ($sum->passing_rate === 0 ? 0 : sprintf('%.2f', $sum->passing_rate * 100)) . "%";
            }else{
                $rowFooterData['rate_impr']= UpdaterTool::createPercentSpan($sum->passing_rate);
            }
        }
        return $rowFooterData;
    }

    private static function getPrefecturePassingRate($prefecture_id){
        return PrefecturePassingRate::where('prefecture_id', $prefecture_id)
            ->whereIn('workbook_id', Self::$wb_id_array)->get();
    }

    private static function getCityPassingRate($city_id){
        return CityPassingRate::where('city_id', $city_id)
            ->whereIn('workbook_id', Self::$wb_id_array)->get();
    }

    private static function getSchoolPassingRate($school_id){
        return SchoolPassingRate::where('school_id', $school_id)
            ->whereIn('workbook_id', Self::$wb_id_array)->get();
    }

    private static function getClassPassingRate($class_id){
        return ClassPassingRate::where('class_id', $class_id)
            ->whereIn('workbook_id', Self::$wb_id_array)->get();
    }

    private static function getPrefectureCurriculumPassingRate($curri_id, $prefecture_id){
        return PrefectureCurriculumPassingRate::where('curriculum_id', $curri_id)
            ->where('prefecture_id', '=', $prefecture_id)->first();
    }

    private static function getCityCurriculumPassingRate($curri_id, $city_id){
        return CityCurriculumPassingRate::where('curriculum_id', $curri_id)
            ->where('city_id', '=', $city_id)->first();
    }

    private static function getSchoolCurriculumPassingRate($curri_id, $school_id){
        return SchoolCurriculumPassingRate::where('curriculum_id', $curri_id)
            ->where('school_id', '=', $school_id)->first();
    }

    private static function getClassCurriculumPassingRate($curri_id, $class_id){
        return ClassCurriculumPassingRate::where('curriculum_id', $curri_id)
            ->where('class_id', '=', $class_id)->first();
    }
}

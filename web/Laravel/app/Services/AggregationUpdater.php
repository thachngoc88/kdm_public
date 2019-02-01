<?php

namespace App\Services;

use App\CityCurriculumPassingRate;
use App\CityPassingRate;
use App\CityUnitPassingRate;
use App\ClassCurriculumPassingRate;
use App\ClassPassingRate;
use App\ClassUnitPassingRate;
use App\Curriculum;
use App\DataTables\AggregationDataTable;
use App\SchoolCurriculumPassingRate;
use App\SchoolPassingRate;
use App\SchoolUnitPassingRate;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AggregationUpdater
{
    public static function updateAggregationCurriculums($log){
        $curriculums = Curriculum::with('units.workbooks')->get();
        foreach($curriculums as $curriculum){
            foreach([
                    AggregationDataTable::MODE_PREFECTURES,
                    AggregationDataTable::MODE_CITIES,
                    AggregationDataTable::MODE_SCHOOLS,
                    AggregationDataTable::MODE_CLASSES] as $mode){

                $tableName = "aggregation_curriculum_{$curriculum->id}_{$mode}";

                self::dropAggregationCurriculums($tableName);
                self::createAggregationCurriculums($tableName, $curriculum, $mode);
                self::insertDataAggregationCurriculums_main($tableName, $curriculum, $mode, $log);
            }
        }

        return $log;
    }

    public static function updateAggregationCurriculumsByMode($log, $mode){
        $curriculums = Curriculum::with('units.workbooks')->get();
        foreach($curriculums as $curriculum){
            $tableName = "aggregation_curriculum_{$curriculum->id}_{$mode}";
            self::dropAggregationCurriculums($tableName);
            self::createAggregationCurriculums($tableName, $curriculum, $mode);
            self::insertDataAggregationCurriculums_main($tableName, $curriculum, $mode, $log);
        }

        return $log;
    }

    private static function dropAggregationCurriculums($tableName){
        Schema::dropIfExists("{$tableName}_main");
        Schema::dropIfExists("{$tableName}_footer");
    }

    private static function createAggregationCurriculums($tableName, $curriculum, $mode){
        Schema::create("{$tableName}_main", function (Blueprint $table) use($curriculum, $mode)
        {
            $table->increments('id');

            if(UpdaterTool::isBelowPrefectureMode($mode)){
                $table->integer('city_id');
                $table->text('city_name');
                if($mode == AggregationDataTable::MODE_CITIES){
                    $table->integer('city_order');
                }

                if(UpdaterTool::isBelowCityMode($mode)){
                    $table->integer('school_id');
                    $table->text('school_name');
                    if($mode == AggregationDataTable::MODE_SCHOOLS){
                        $table->integer('city_order');
                        $table->integer('school_order');
                    }
                    if(UpdaterTool::isBelowSchoolMode($mode)){
                        $table->integer('class_id');
                        $table->text('class_name');
                    }
                }
            }

            // for filtering

            foreach($curriculum->units as $unit){
                $un = $unit->number;
                $colName = "passing_rate_unit_{$un}";
                $table->text($colName)->nullable();

                foreach($unit->workbooks as $workbook){
                    $wn = $workbook->number;
                    $colName = "passing_rate_unit_{$un}_workbook_{$wn}";
                    $table->text($colName)->nullable();
                }
            }

            $table->text('passing_rate_class');

            $table->integer('marking_log_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('marking_log_id', "agg_curr_{$curriculum->id}_{$mode}_marking_log_id")->references('id')->on('marking_logs');
        });

        /*Schema::create("{$tableName}_footer", function (Blueprint $table) {
            $table->increments('id');
            $table->string('session_id');
        });*/
    }

    private static function insertDataAggregationCurriculums_main($tableName, $curriculum, $mode, $log){
        switch($mode){
            case AggregationDataTable::MODE_CITIES:
                self::insertDataAggregationCurriculumsCities_main($tableName, $curriculum, $log);
                break;
            case AggregationDataTable::MODE_SCHOOLS:
                self::insertDataAggregationCurriculumsSchools_main($tableName, $curriculum, $log);
                break;
            case AggregationDataTable::MODE_CLASSES:
                self::insertDataAggregationCurriculumsClasses_main($tableName, $curriculum, $log);
                break;
        }
    }

    private static function insertDataAggregationCurriculumsClasses_main($tableName, $curriculum, $log){

        echo("insertDataAggregationCurriculumsClasses_main" . PHP_EOL);

        $data = ['marking_log_id' => $log->id];

       $classes = DB::table("classes as CL")
            ->join('grades as G', 'CL.grade_id', '=', 'G.id')
            ->join('schools as S', 'CL.school_id', '=', 'S.id')
            ->join('cities as C', 'S.city_id', '=', 'C.id')
            ->select('CL.*', 'G.number as grade_number', 'S.name as school_name', 'C.name as city_name', 'C.id as city_id', 'S.id as school_id')
            ->where('G.number', '=', $curriculum->grade->number)
            ->whereNull('CL.deleted_at')
            ->whereNull('G.deleted_at')
            ->whereNull('S.deleted_at')
            ->whereNull('C.deleted_at')
            ->get();

        foreach ($classes as $class){

            $data['class_id']    = $class->id;
            $data['city_name']   = $class->city_name;
            $data['school_name'] = $class->school_name;
            $data['class_name']  = "{$class->grade_number}-{$class->name}";

            $data['city_id'] = $class->city_id;
            $data['school_id'] = $class->school_id;

            echo("curriculum_id: {$curriculum->id}" . PHP_EOL);
            echo("class_id: {$data['class_id']}" . PHP_EOL);
            echo("class_name: {$data['class_name']}" . PHP_EOL);
            echo("school_name: {$data['school_name']}" . PHP_EOL);
            echo("city_name: {$data['city_name']}" . PHP_EOL);

            $classCurriculumPassingRate = ClassCurriculumPassingRate
                ::where('class_id', '=', $class->id)
                ->where('curriculum_id', '=', $curriculum->id)
                ->value('passing_rate');


            $data['passing_rate_class'] = UpdaterTool::createPercentSpan($classCurriculumPassingRate);

            foreach($curriculum->units as $unit){
                $un = $unit->number;
                $colName = "passing_rate_unit_{$un}";

                $classUnitPassingRate = ClassUnitPassingRate
                    ::where('class_id', $class->id)
                    ->where('unit_id',  $unit->id)
                    ->value('passing_rate');

                $data[$colName] = UpdaterTool::createPercentSpan($classUnitPassingRate);

                foreach($unit->workbooks as $workbook) {
                    $wn = $workbook->number;
                    $colName = "passing_rate_unit_{$un}_workbook_{$wn}";
                    $classPassingRate = ClassPassingRate
                        ::where('class_id',    $class->id)
                        ->where('workbook_id', $workbook->id)
                        ->value('passing_rate');

                    $data[$colName] = UpdaterTool::createPercentSpan($classPassingRate);
                }
            }

            DB::table("{$tableName}_main")->insert($data);
        }
    }

    private static function insertDataAggregationCurriculumsSchools_main($tableName, $curriculum, $log){

        echo("insertDataAggregationCurriculumsSchools_main" . PHP_EOL);

        $data = ['marking_log_id' => $log->id];

        $schools = DB::table("schools as S")
            ->join('cities as C', 'S.city_id', '=', 'C.id')
            ->whereNull('S.deleted_at')
            ->whereNull('C.deleted_at')
            ->select('S.id as school_id', 'S.name as school_name','S.order as school_order',  'C.name as city_name', 'C.order as city_order', 'C.id as city_id', 'S.id as school_id')
            ->orderBy('school_order', 'asc')->orderBy('city_order', 'asc')
            ->get();

        foreach ($schools as $school){

            $data['city_name']   = $school->city_name;
            $data['city_order'] = $school->city_order;
            $data['school_name'] = $school->school_name;
            $data['school_order'] = $school->school_order;

            $data['city_id'] = $school->city_id;
            $data['school_id'] = $school->school_id;

            $schoolCurriculumPassingRate = SchoolCurriculumPassingRate
                ::where('school_id', '=', $school->school_id)
                ->where('curriculum_id', '=', $curriculum->id)
                ->value('passing_rate');

            $data['passing_rate_class'] = UpdaterTool::createPercentSpan($schoolCurriculumPassingRate);

            foreach($curriculum->units as $unit){
                $un = $unit->number;
                $colName = "passing_rate_unit_{$un}";

                $schoolUnitPassingRate = SchoolUnitPassingRate
                    ::where('school_id', $school->school_id)
                    ->where('unit_id',  $unit->id)
                    ->value('passing_rate');

                $data[$colName] = UpdaterTool::createPercentSpan($schoolUnitPassingRate);

                foreach($unit->workbooks as $workbook) {
                    $wn = $workbook->number;
                    $colName = "passing_rate_unit_{$un}_workbook_{$wn}";
                    $schoolPassingRate = SchoolPassingRate
                        ::where('school_id',    $school->school_id)
                        ->where('workbook_id', $workbook->id)
                        ->value('passing_rate');

                    $data[$colName] = UpdaterTool::createPercentSpan($schoolPassingRate);
                }
            }

            DB::table("{$tableName}_main")->insert($data);
        }
    }

    private static function insertDataAggregationCurriculumsCities_main($tableName, $curriculum, $log){

        echo("insertDataAggregationCurriculumsCities_main" . PHP_EOL);

        $data = ['marking_log_id' => $log->id];

        $cities = \App\City::
                select('name as city_name', 'id as city_id','order as city_order')
                ->orderBy('order', 'asc')
                ->get();

        foreach ($cities as $city){

            $data['city_name'] = $city->city_name;
            $data['city_id'] = $city->city_id;
            $data['city_order'] = $city->city_order;


            $cityCurriculumPassingRate = CityCurriculumPassingRate
                ::where('city_id', '=', $city->city_id)
                ->where('curriculum_id', '=', $curriculum->id)
                ->value('passing_rate');

            $data['passing_rate_class'] = UpdaterTool::createPercentSpan($cityCurriculumPassingRate);

            foreach($curriculum->units as $unit){
                $un = $unit->number;
                $colName = "passing_rate_unit_{$un}";

                $cityUnitPassingRate = CityUnitPassingRate
                    ::where('city_id', $city->city_id)
                    ->where('unit_id',  $unit->id)
                    ->value('passing_rate');

                $data[$colName] = UpdaterTool::createPercentSpan($cityUnitPassingRate);

                foreach($unit->workbooks as $workbook) {
                    $wn = $workbook->number;
                    $colName = "passing_rate_unit_{$un}_workbook_{$wn}";
                    $cityPassingRate = CityPassingRate
                        ::where('city_id',    $city->city_id)
                        ->where('workbook_id', $workbook->id)
                        ->value('passing_rate');

                    $data[$colName] = UpdaterTool::createPercentSpan($cityPassingRate);
                }
            }
            DB::table("{$tableName}_main")->insert($data);
        }
    }
}

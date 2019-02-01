<?php

namespace App\Services;

use App\ChallengeUserUnitStatus;
use App\ChallengeUserWorkbookStatus;
use App\City;
use App\CityCurriculumPassingRate;
use App\CityPassingRate;
use App\CityUnitPassingRate;
use App\ClassCurriculumPassingRate;
use App\ClassPassingRate;
use App\ClassUnitPassingRate;
use App\Curriculum;
use App\DataTables\AggregationDataTable;
use App\Grade;
use App\Klass;
use App\MarkingLog;
use App\Prefecture;
use App\PrefectureCurriculumPassingRate;
use App\PrefecturePassingRate;
use App\PrefectureUnitPassingRate;
use App\School;
use App\SchoolCurriculumPassingRate;
use App\SchoolPassingRate;
use App\SchoolUnitPassingRate;
use App\Unit;
use App\Workbook;
use Illuminate\Support\Facades\DB;

class PassingRateUpdater
{

    public static function updatePassingRates($log){
        self::updateClassPassingRates($log);
        self::updateClassUnitPassingRates($log);
        self::updateClassCurriculumPassingRates($log);
        self::updateSchoolPassingRates($log);
        self::updateSchoolUnitPassingRates($log);
        self::updateSchoolCurriculumPassingRates($log);
        self::updateCityPassingRates($log);
        self::updateCityUnitPassingRates($log);
        self::updateCityCurriculumPassingRates($log);
        self::updatePrefecturePassingRates($log);
        self::updatePrefectureUnitPassingRates($log);
        self::updatePrefectureCurriculumPassingRates($log);

        return $log;
    }

    public static function updatePassingRatesByMode($log, $mode){

        switch($mode){
            case AggregationDataTable::MODE_CLASSES:
                self::updateClassPassingRates($log);
                self::updateClassUnitPassingRates($log);
                self::updateClassCurriculumPassingRates($log);
                break;
            case AggregationDataTable::MODE_SCHOOLS:
                self::updateSchoolPassingRates($log);
                self::updateSchoolUnitPassingRates($log);
                self::updateSchoolCurriculumPassingRates($log);
                break;
            case AggregationDataTable::MODE_CITIES:
                self::updateCityPassingRates($log);
                self::updateCityUnitPassingRates($log);
                self::updateCityCurriculumPassingRates($log);
                break;
            case AggregationDataTable::MODE_PREFECTURES:
                self::updatePrefecturePassingRates($log);
                self::updatePrefectureUnitPassingRates($log);
                self::updatePrefectureCurriculumPassingRates($log);
                break;
        }

        return $log;
    }

    private static function updatePrefecturePassingRates($log){
        echo("-updatePrefecturePassingRates- marking" . PHP_EOL);

        $prefectures = Prefecture::all();
        echo("Prefectures size: " . count($prefectures) . PHP_EOL);

        $workbooks = Workbook::all();
        echo("Workbooks size: " . count($workbooks) . PHP_EOL);

        $max = 99999; // TODO count
        foreach($prefectures as $prefecture){
            foreach($workbooks as $workbook){
                self::savePrefecturePassingRate($prefecture, $workbook, $log);
                echo("saved PrefecturePassingRate Workbook ID: {$workbook->id}" . PHP_EOL);
            }
            if(--$max <= 0){
                break;
            }
        }
    }

    private static function updatePrefectureUnitPassingRates($log){
        echo("-updatePrefectureUnitPassingRates- marking" . PHP_EOL);

        $prefectures = Prefecture::all();
        echo("Prefectures size: " . count($prefectures) . PHP_EOL);

        $units = Unit::all();
        echo("Units size: " .count($units) . PHP_EOL);

        $max = 99999; // TODO count
        foreach($prefectures as $prefecture){
            foreach($units as $unit){
                self::savePrefectureUnitPassingRate($prefecture, $unit, $log);
                echo("saved PrefectureUnitPassingRate Unit ID: {$unit->id}" . PHP_EOL);
            }
            if(--$max <= 0){
                break;
            }
        }
    }

    private static function updatePrefectureCurriculumPassingRates($log){
        echo("-updatePrefectureCurriculumPassingRates- marking" . PHP_EOL);

        $prefectures = Prefecture::all();
        echo("Prefectures size: " . count($prefectures) . PHP_EOL);

        $curriculums = Curriculum::all();
        echo("Curriculums size: " . count($curriculums) . PHP_EOL);

        $max = 99999; // TODO count
        foreach($prefectures as $prefecture){
            foreach($curriculums as $curriculum){
                self::savePrefectureCurriculumPassingRate($prefecture, $curriculum, $log);
                echo("saved PrefectureCurriculumPassingRate Curriculum ID: {$curriculum->id}" . PHP_EOL);
            }
            if(--$max <= 0){
                break;
            }
        }
    }

    private static function savePrefecturePassingRate(Prefecture $prefecture, Workbook $workbook, MarkingLog $log){
        $prefecturePassingRate = PrefecturePassingRate::firstOrNew([
            'prefecture_id' => $prefecture->id,
            'workbook_id' => $workbook->id,
        ]);

        $prefecturePassingRate = self::fillPrefecturePassingRate($prefecturePassingRate, $prefecture, $workbook);
        $prefecturePassingRate->marking_log_id = $log->id;
        $prefecturePassingRate->save();
    }

    private static function savePrefectureUnitPassingRate(Prefecture $prefecture, Unit $unit, MarkingLog $log){
        $prefectureUnitPassingRate = PrefectureUnitPassingRate::firstOrNew([
            'prefecture_id' => $prefecture->id,
            'unit_id' => $unit->id,
        ]);

        $prefectureUnitPassingRate = self::fillPrefectureUnitPassingRate($prefectureUnitPassingRate, $prefecture, $unit);
        $prefectureUnitPassingRate->marking_log_id = $log->id;
        $prefectureUnitPassingRate->save();
    }

    private static function savePrefectureCurriculumPassingRate(Prefecture $prefecture, Curriculum $curriculum, MarkingLog $log){
        $prefectureCurriculumPassingRate = PrefectureCurriculumPassingRate::firstOrNew([
            'prefecture_id' => $prefecture->id,
            'curriculum_id' => $curriculum->id,
        ]);

        $prefectureCurriculumPassingRate = self::fillPrefectureCurriculumPassingRate($prefectureCurriculumPassingRate, $prefecture, $curriculum);
        $prefectureCurriculumPassingRate->marking_log_id = $log->id;
        $prefectureCurriculumPassingRate->save();
    }

    private static function fillPrefecturePassingRate(PrefecturePassingRate $prefecturePassingRate, Prefecture $prefecture, Workbook $workbook){
        $challengeUserWorkbookStatuses = ChallengeUserWorkbookStatus
            ::join('challenge_users as CU','challenge_user_workbook_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->join('cities as CT','S.city_id', '=', 'CT.id')
            ->join('workbooks as W','challenge_user_workbook_statuses.workbook_id', '=', 'W.id')
            ->groupBy('challenge_user_workbook_statuses.status')
            ->where('CT.prefecture_id', '=', $prefecture->id)
            ->where('challenge_user_workbook_statuses.workbook_id', '=', $workbook->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->whereNull('CT.deleted_at')
            ->whereNull('W.deleted_at')
            ->select(['challenge_user_workbook_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserWorkbookStatuses as $challengeUserWorkbookStatus) {
            $statusCount = $challengeUserWorkbookStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserWorkbookStatus->status >= 2){
                $passingCount += $statusCount;
            }
        }
        $prefecturePassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $prefecturePassingRate;
    }

    private static function fillPrefectureUnitPassingRate(PrefectureUnitPassingRate $prefectureUnitPassingRate, Prefecture $prefecture, Unit $unit){
        $challengeUserUnitStatuses = ChallengeUserUnitStatus
            ::join('challenge_users as CU','challenge_user_unit_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->join('cities as CT','S.city_id', '=', 'CT.id')
            ->groupBy('challenge_user_unit_statuses.status')
            ->where('CT.prefecture_id', '=', $prefecture->id)
            ->where('challenge_user_unit_statuses.unit_id', '=', $unit->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->whereNull('CT.deleted_at')
            ->select(['challenge_user_unit_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserUnitStatuses as $challengeUserUnitStatus) {
            $statusCount = $challengeUserUnitStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserUnitStatus->status >= 2){
                $passingCount += $challengeUserUnitStatus->status_count;
            }
        }
        $prefectureUnitPassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $prefectureUnitPassingRate;
    }

    private static function fillPrefectureCurriculumPassingRate(PrefectureCurriculumPassingRate $prefectureCurriculumPassingRate, Prefecture $prefecture, Curriculum $curriculum){
        $challengeUserWorkbookStatuses = ChallengeUserWorkbookStatus
            ::join('challenge_users as CU','challenge_user_workbook_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->join('cities as CT','S.city_id', '=', 'CT.id')
            ->join('workbooks as W','challenge_user_workbook_statuses.workbook_id', '=', 'W.id')
            ->join('units as UN','W.unit_id', '=', 'UN.id')
            ->groupBy('challenge_user_workbook_statuses.status')
            ->where('CT.prefecture_id', '=', $prefecture->id)
            ->where('UN.curriculum_id', '=', $curriculum->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->whereNull('CT.deleted_at')
            ->whereNull('W.deleted_at')
            ->whereNull('UN.deleted_at')
            ->select(['challenge_user_workbook_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserWorkbookStatuses as $challengeUserWorkbookStatus) {
            $statusCount = $challengeUserWorkbookStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserWorkbookStatus->status >= 2){
                $passingCount += $challengeUserWorkbookStatus->status_count;
            }
        }
        $prefectureCurriculumPassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $prefectureCurriculumPassingRate;
    }








    private static function updateCityPassingRates($log){
        echo("-updateCityPassingRates- marking" . PHP_EOL);

        $cities = City::all();
        echo("Cities size: " . count($cities) . PHP_EOL);

        $workbooks = Workbook::all();
        echo("Workbooks size: " . count($workbooks) . PHP_EOL);

        $max = 99999; // TODO count
        foreach($cities as $city){
            foreach($workbooks as $workbook){
                self::saveCityPassingRate($city, $workbook, $log);
                echo("saved CityPassingRate Workbook ID: {$workbook->id}" . PHP_EOL);
            }
            if(--$max <= 0){
                break;
            }
        }
    }

    private static function updateCityUnitPassingRates($log){
        echo("-updateCityUnitPassingRates- marking" . PHP_EOL);

        $cities = City::all();
        echo("Cities size: " . count($cities) . PHP_EOL);

        $units = Unit::all();
        echo("Units size: " .count($units) . PHP_EOL);

        $max = 99999; // TODO count
        foreach($cities as $city){
            foreach($units as $unit){
                self::saveCityUnitPassingRate($city, $unit, $log);
                echo("saved CityUnitPassingRate Unit ID: {$unit->id}" . PHP_EOL);
            }
            if(--$max <= 0){
                break;
            }
        }
    }

    private static function updateCityCurriculumPassingRates($log){
        echo("-updateCityCurriculumPassingRates- marking" . PHP_EOL);

        $cities = City::all();
        echo("Cities size: " . count($cities) . PHP_EOL);

        $curriculums = Curriculum::all();
        echo("Curriculums size: " . count($curriculums) . PHP_EOL);

        $max = 99999; // TODO count
        foreach($cities as $city){
            foreach($curriculums as $curriculum){
                self::saveCityCurriculumPassingRate($city, $curriculum, $log);
                echo("saved CityCurriculumPassingRate Curriculum ID: {$curriculum->id}" . PHP_EOL);
            }
            if(--$max <= 0){
                break;
            }
        }
    }

    private static function saveCityPassingRate(City $city, Workbook $workbook, MarkingLog $log){
        $cityPassingRate = CityPassingRate::firstOrNew([
            'city_id' => $city->id,
            'workbook_id' => $workbook->id,
        ]);

        $cityPassingRate = self::fillCityPassingRate($cityPassingRate, $city, $workbook);
        $cityPassingRate->marking_log_id = $log->id;
        $cityPassingRate->save();
    }

    private static function saveCityUnitPassingRate(City $city, Unit $unit, MarkingLog $log){
        $cityUnitPassingRate = CityUnitPassingRate::firstOrNew([
            'city_id' => $city->id,
            'unit_id' => $unit->id,
        ]);

        $cityUnitPassingRate = self::fillCityUnitPassingRate($cityUnitPassingRate, $city, $unit);
        $cityUnitPassingRate->marking_log_id = $log->id;
        $cityUnitPassingRate->save();
    }

    private static function saveCityCurriculumPassingRate(City $city, Curriculum $curriculum, MarkingLog $log){
        $cityCurriculumPassingRate = CityCurriculumPassingRate::firstOrNew([
            'city_id' => $city->id,
            'curriculum_id' => $curriculum->id,
        ]);

        $cityCurriculumPassingRate = self::fillCityCurriculumPassingRate($cityCurriculumPassingRate, $city, $curriculum);
        $cityCurriculumPassingRate->marking_log_id = $log->id;
        $cityCurriculumPassingRate->save();
    }

    private static function fillCityPassingRate(CityPassingRate $cityPassingRate, City $city, Workbook $workbook){
        $challengeUserWorkbookStatuses = ChallengeUserWorkbookStatus
            ::join('challenge_users as CU','challenge_user_workbook_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->join('workbooks as W','challenge_user_workbook_statuses.workbook_id', '=', 'W.id')
            ->groupBy('challenge_user_workbook_statuses.status')
            ->where('S.city_id', '=', $city->id)
            ->where('challenge_user_workbook_statuses.workbook_id', '=', $workbook->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->whereNull('W.deleted_at')
            ->select(['challenge_user_workbook_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserWorkbookStatuses as $challengeUserWorkbookStatus) {
            $statusCount = $challengeUserWorkbookStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserWorkbookStatus->status >= 2){
                $passingCount += $statusCount;
            }
        }
        $cityPassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $cityPassingRate;
    }

    private static function fillCityUnitPassingRate(CityUnitPassingRate $cityUnitPassingRate, City $city, Unit $unit){
        $challengeUserUnitStatuses = ChallengeUserUnitStatus
            ::join('challenge_users as CU','challenge_user_unit_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->groupBy('challenge_user_unit_statuses.status')
            ->where('S.city_id', '=', $city->id)
            ->where('challenge_user_unit_statuses.unit_id', '=', $unit->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->select(['challenge_user_unit_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserUnitStatuses as $challengeUserUnitStatus) {
            $statusCount = $challengeUserUnitStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserUnitStatus->status >= 2){
                $passingCount += $challengeUserUnitStatus->status_count;
            }
        }
        $cityUnitPassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $cityUnitPassingRate;
    }

    private static function fillCityCurriculumPassingRate(CityCurriculumPassingRate $cityCurriculumPassingRate, City $city, Curriculum $curriculum){
        $challengeUserWorkbookStatuses = ChallengeUserWorkbookStatus
            ::join('challenge_users as CU','challenge_user_workbook_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->join('workbooks as W','challenge_user_workbook_statuses.workbook_id', '=', 'W.id')
            ->join('units as UN','W.unit_id', '=', 'UN.id')
            ->groupBy('challenge_user_workbook_statuses.status')
            ->where('S.city_id', '=', $city->id)
            ->where('UN.curriculum_id', '=', $curriculum->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->whereNull('W.deleted_at')
            ->whereNull('UN.deleted_at')
            ->select(['challenge_user_workbook_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserWorkbookStatuses as $challengeUserWorkbookStatus) {
            $statusCount = $challengeUserWorkbookStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserWorkbookStatus->status >= 2){
                $passingCount += $challengeUserWorkbookStatus->status_count;
            }
        }
        $cityCurriculumPassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $cityCurriculumPassingRate;
    }










    private static function updateSchoolPassingRates($log){
        echo("-updateSchoolPassingRates- marking" . PHP_EOL);

        $schools = School::all();
        echo("Schools size: " . count($schools) . PHP_EOL);

        $workbooks = Workbook::all();
        echo("Workbooks size: " .count($workbooks) . PHP_EOL);

        $max = 99999; // TODO count
        foreach($schools as $school){
            foreach($workbooks as $workbook){
                self::saveSchoolPassingRate($school, $workbook, $log);
                echo("saved SchoolPassingRate Workbook ID: {$workbook->id}" . PHP_EOL);
            }
            if(--$max <= 0){
                break;
            }
        }
    }

    private static function updateSchoolUnitPassingRates($log){
        echo("-updateSchoolUnitPassingRates- marking" . PHP_EOL);

        $schools = School::all();
        echo("Schools size: " . count($schools) . PHP_EOL);

        $units = Unit::all();
        echo("Units size: " .count($units) . PHP_EOL);

        $max = 99999; // TODO count
        foreach($schools as $school){
            foreach($units as $unit){
                self::saveSchoolUnitPassingRate($school, $unit, $log);
                echo("saved SchoolUnitPassingRate Unit ID: {$unit->id}" . PHP_EOL);
            }
            if(--$max <= 0){
                break;
            }
        }
    }

    private static function updateSchoolCurriculumPassingRates($log){
        echo("-updateSchoolCurriculumPassingRates- marking" . PHP_EOL);

        $schools = School::all();
        echo("Schools size: " . count($schools) . PHP_EOL);

        $curriculums = Curriculum::all();
        echo("Curriculums size: " . count($curriculums) . PHP_EOL);

        $max = 99999; // TODO count
        foreach($schools as $school){
            foreach($curriculums as $curriculum){
                self::saveSchoolCurriculumPassingRate($school, $curriculum, $log);
                echo("saved SchoolCurriculumPassingRate Curriculum ID: {$curriculum->id}" . PHP_EOL);
            }
            if(--$max <= 0){
                break;
            }
        }
    }

    private static function saveSchoolPassingRate(School $school, Workbook $workbook, MarkingLog $log){
        $schoolPassingRate = SchoolPassingRate::firstOrNew([
            'school_id' => $school->id,
            'workbook_id' => $workbook->id,
        ]);
        $schoolPassingRate = self::fillSchoolPassingRate($schoolPassingRate, $school, $workbook);
        $schoolPassingRate->marking_log_id = $log->id;
        $schoolPassingRate->save();
    }

    private static function saveSchoolUnitPassingRate(School $school, Unit $unit, MarkingLog $log){
        $schoolUnitPassingRate = SchoolUnitPassingRate::firstOrNew([
            'school_id' => $school->id,
            'unit_id' => $unit->id,
        ]);

        $schoolUnitPassingRate = self::fillSchoolUnitPassingRate($schoolUnitPassingRate, $school, $unit);
        $schoolUnitPassingRate->marking_log_id = $log->id;

        $schoolUnitPassingRate->save();
    }

    private static function saveSchoolCurriculumPassingRate(School $school, Curriculum $curriculum, MarkingLog $log){
        $schoolCurriculumPassingRate = SchoolCurriculumPassingRate::firstOrNew([
            'school_id' => $school->id,
            'curriculum_id' => $curriculum->id,
        ]);

        $schoolCurriculumPassingRate = self::fillSchoolCurriculumPassingRate($schoolCurriculumPassingRate, $school, $curriculum);
        $schoolCurriculumPassingRate->marking_log_id = $log->id;
        $schoolCurriculumPassingRate->save();
    }

    private static function fillSchoolPassingRate(SchoolPassingRate $schoolPassingRate, School $school, Workbook $workbook){
        $challengeUserWorkbookStatuses = ChallengeUserWorkbookStatus
            ::join('challenge_users as CU','challenge_user_workbook_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->join('workbooks as W','challenge_user_workbook_statuses.workbook_id', '=', 'W.id')
            ->groupBy('challenge_user_workbook_statuses.status')
            ->where('S.id', '=', $school->id)
            ->where('challenge_user_workbook_statuses.workbook_id', '=', $workbook->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->whereNull('W.deleted_at')
            ->select(['challenge_user_workbook_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserWorkbookStatuses as $challengeUserWorkbookStatus) {
            $statusCount = $challengeUserWorkbookStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserWorkbookStatus->status >= 2){
                $passingCount += $challengeUserWorkbookStatus->status_count;
            }
        }
        $schoolPassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $schoolPassingRate;
    }

    private static function fillSchoolUnitPassingRate(SchoolUnitPassingRate $schoolUnitPassingRate, School $school, Unit $unit){
        $challengeUserUnitStatuses = ChallengeUserUnitStatus
            ::join('challenge_users as CU','challenge_user_unit_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->groupBy('challenge_user_unit_statuses.status')
            ->where('S.id', '=', $school->id)
            ->where('challenge_user_unit_statuses.unit_id', '=', $unit->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->select(['challenge_user_unit_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserUnitStatuses as $challengeUserUnitStatus) {
            $statusCount = $challengeUserUnitStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserUnitStatus->status >= 2){
                $passingCount += $challengeUserUnitStatus->status_count;
            }
        }

        $schoolUnitPassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $schoolUnitPassingRate;
    }

    private static function fillSchoolCurriculumPassingRate(SchoolCurriculumPassingRate $schoolCurriculumPassingRate, School $school, Curriculum $curriculum){
        $challengeUserWorkbookStatuses = ChallengeUserWorkbookStatus
            ::join('challenge_users as CU','challenge_user_workbook_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->join('workbooks as W','challenge_user_workbook_statuses.workbook_id', '=', 'W.id')
            ->join('units as UN','W.unit_id', '=', 'UN.id')
            ->groupBy('challenge_user_workbook_statuses.status')
            ->where('S.id', '=', $school->id)
            ->where('UN.curriculum_id', '=', $curriculum->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->whereNull('W.deleted_at')
            ->whereNull('UN.deleted_at')
            ->select(['challenge_user_workbook_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserWorkbookStatuses as $challengeUserWorkbookStatus) {
            $statusCount = $challengeUserWorkbookStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserWorkbookStatus->status >= 2){
                $passingCount += $challengeUserWorkbookStatus->status_count;
            }
        }
        $schoolCurriculumPassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $schoolCurriculumPassingRate;
    }




    private static function updateClassPassingRates($log){
        $gradeNumbers = Grade::get()->pluck('number');
        foreach($gradeNumbers as $gradeNumber){
            echo("-updateClassPassingRates- Grand number: {$gradeNumber} - marking" . PHP_EOL);

            $classes = UpdaterTool::getClassesOfGradeNumber($gradeNumber);
            echo("Classes size: " . count($classes) . PHP_EOL);

            $workbooks = UpdaterTool::getWorkbooksOfGradeNumber($gradeNumber);
            echo("Workbooks size: " .count($workbooks) . PHP_EOL);

            $max = 99999; // TODO count
            foreach($classes as $class){
                foreach($workbooks as $workbook){
                    self::saveClassPassingRate($class, $workbook, $log);
                    echo("saved ClassPassingRate Workbook ID: {$workbook->id} Grand number: {$gradeNumber}" . PHP_EOL);
                }
                if(--$max <= 0){
                    break;
                }
            }
        }
    }

    private static function updateClassUnitPassingRates($log){
        $gradeNumbers = Grade::get()->pluck('number');
        foreach($gradeNumbers as $gradeNumber){
            echo("-updateClassUnitPassingRates- Grand number: {$gradeNumber} - marking" . PHP_EOL);

            $classes = UpdaterTool::getClassesOfGradeNumber($gradeNumber);
            echo("Classes size: " . count($classes) . PHP_EOL);

            $units = UpdaterTool::getUnitsOfGradeNumber($gradeNumber);
            echo("Units size: " .count($units) . PHP_EOL);

            $max = 99999; // TODO count
            foreach($classes as $class){
                foreach($units as $unit){
                    self::saveClassUnitPassingRate($class, $unit, $log);
                    echo("saved ClassUnitPassingRate Unit ID: {$unit->id} Grand number: {$gradeNumber}" . PHP_EOL);
                }
                if(--$max <= 0){
                    break;
                }
            }
        }
    }

    private static function updateClassCurriculumPassingRates($log){
        $gradeNumbers = Grade::get()->pluck('number');
        foreach($gradeNumbers as $gradeNumber){
            echo("-updateClassCurriculumPassingRates- Grand number: {$gradeNumber} - marking" . PHP_EOL);

            $classes = UpdaterTool::getClassesOfGradeNumber($gradeNumber);
            echo("Classes size: " . count($classes) . PHP_EOL);

            $curriculums = UpdaterTool::getCurriculumsOfGradeNumber($gradeNumber);
            echo("Curriculums size: " . count($curriculums) . PHP_EOL);

            $max = 99999; // TODO count
            foreach($classes as $class){
                foreach($curriculums as $curriculum){
                    self::saveClassCurriculumPassingRate($class, $curriculum, $log);
                    echo("saved ClassCurriculumPassingRate Curriculum ID: {$curriculum->id} Grand number: {$gradeNumber}" . PHP_EOL);
                }
                if(--$max <= 0){
                    break;
                }
            }
        }
    }

    private static function saveClassPassingRate(Klass $class, Workbook $workbook, MarkingLog $log){
        $classPassingRate = ClassPassingRate::firstOrNew([
            'class_id' => $class->id,
            'workbook_id' => $workbook->id,
        ]);
        $classPassingRate = self::fillClassPassingRate($classPassingRate, $class, $workbook);
        $classPassingRate->marking_log_id = $log->id;
        $classPassingRate->save();
    }

    private static function saveClassUnitPassingRate(Klass $class, Unit $unit, MarkingLog $log){
        $classUnitPassingRate = ClassUnitPassingRate::firstOrNew([
            'class_id' => $class->id,
            'unit_id' => $unit->id,
        ]);

        $classUnitPassingRate = self::fillClassUnitPassingRate($classUnitPassingRate, $class, $unit);
        $classUnitPassingRate->marking_log_id = $log->id;
        $classUnitPassingRate->save();
    }

    private static function saveClassCurriculumPassingRate(Klass $class, Curriculum $curriculum, MarkingLog $log){
        $classCurriculumPassingRate = ClassCurriculumPassingRate::firstOrNew([
            'class_id' => $class->id,
            'curriculum_id' => $curriculum->id,
        ]);

        $classCurriculumPassingRate = self::fillClassCurriculumPassingRate($classCurriculumPassingRate, $class, $curriculum);
        $classCurriculumPassingRate->marking_log_id = $log->id;
        $classCurriculumPassingRate->save();
    }

    private static function fillClassPassingRate(ClassPassingRate $classPassingRate, Klass $class, Workbook $workbook){
        $challengeUserWorkbookStatuses = ChallengeUserWorkbookStatus
            ::join('challenge_users as CU','challenge_user_workbook_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->join('workbooks as W','challenge_user_workbook_statuses.workbook_id', '=', 'W.id')
            ->groupBy('challenge_user_workbook_statuses.status')
            ->where('C.id', '=', $class->id)
            ->where('challenge_user_workbook_statuses.workbook_id', '=', $workbook->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->whereNull('W.deleted_at')
            ->select(['challenge_user_workbook_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserWorkbookStatuses as $challengeUserWorkbookStatus) {
            $statusCount = $challengeUserWorkbookStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserWorkbookStatus->status >= 2){
                $passingCount += $challengeUserWorkbookStatus->status_count;
            }
        }
        $classPassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $classPassingRate;
    }

    private static function fillClassUnitPassingRate(ClassUnitPassingRate $classUnitPassingRate, Klass $class, Unit $unit){
        $challengeUserUnitStatuses = ChallengeUserUnitStatus
            ::join('challenge_users as CU','challenge_user_unit_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->groupBy('challenge_user_unit_statuses.status')
            ->where('C.id', '=', $class->id)
            ->where('challenge_user_unit_statuses.unit_id', '=', $unit->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->select(['challenge_user_unit_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserUnitStatuses as $challengeUserUnitStatus) {
            $statusCount = $challengeUserUnitStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserUnitStatus->status >= 2){
                $passingCount += $challengeUserUnitStatus->status_count;
            }
        }
        $classUnitPassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $classUnitPassingRate;
    }

    private static function fillClassCurriculumPassingRate(ClassCurriculumPassingRate $classCurriculumPassingRate, Klass $class, Curriculum $curriculum){
        $challengeUserWorkbookStatuses = ChallengeUserWorkbookStatus
            ::join('challenge_users as CU','challenge_user_workbook_statuses.challenge_user_id', '=', 'CU.id')
            ->join('users as U','CU.user_id', '=', 'U.id')
            ->join('classes as C','CU.class_id', '=', 'C.id')
            ->join('schools as S','C.school_id', '=', 'S.id')
            ->join('workbooks as W','challenge_user_workbook_statuses.workbook_id', '=', 'W.id')
            ->join('units as UN','W.unit_id', '=', 'UN.id')
            ->groupBy('challenge_user_workbook_statuses.status')
            ->where('C.id', '=', $class->id)
            ->where('UN.curriculum_id', '=', $curriculum->id)
            ->where('U.enabled', '=', 1)
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->whereNull('C.deleted_at')
            ->whereNull('S.deleted_at')
            ->whereNull('W.deleted_at')
            ->whereNull('UN.deleted_at')
            ->select(['challenge_user_workbook_statuses.status', DB::raw('Count(*) as status_count')])
            ->get();

        $targetCount = 0;
        $passingCount = 0;

        foreach ($challengeUserWorkbookStatuses as $challengeUserWorkbookStatus) {
            $statusCount = $challengeUserWorkbookStatus->status_count;
            $targetCount += $statusCount;
            if($challengeUserWorkbookStatus->status >= 2){
                $passingCount += $challengeUserWorkbookStatus->status_count;
            }
        }
        $classCurriculumPassingRate->passing_rate = $targetCount > 0 ? ($passingCount / $targetCount) : 0;

        return $classCurriculumPassingRate;
    }
}

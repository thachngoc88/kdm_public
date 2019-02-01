<?php

namespace App\Http\Controllers;

use App\City;
use App\Curriculum;
use App\DataTables\AggregationDataTable;
use App\Grade;
use App\Klass;
use App\School;
use App\Services\AggregationService;
use App\Services\WorkbookHashMaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AggregationController extends KdmDatatablesController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
                            AggregationDataTable $dataTable,
                            $curriculumId = 1,
                            $aggregationMode = null,
                            $cityId = null,
                            $schoolId = null,
                            $classId = null){

        $roler = App::make('roler');
        if ($roler->isCityUser()){
            $cityId = $roler->getRole()->city->id;
            if(!$aggregationMode){
                $aggregationMode = AggregationDataTable::MODE_SCHOOLS;
            }
        }elseif ($roler->isSchoolUser()){
            $school   = $roler->getRole()->school;
            $cityId   = $school->city->id;
            $schoolId = $school->id;
            if(!$aggregationMode){
                $aggregationMode = AggregationDataTable::MODE_CLASSES;
            }
        }else{
            if(!$aggregationMode){
                $aggregationMode = AggregationDataTable::MODE_CITIES;
            }
        }

        $dataTable->setCurriculum(Curriculum::find($curriculumId));
        $dataTable->setAggregationMode($aggregationMode);
        $dataTable->setCityId($cityId);
        $dataTable->setSchoolId($schoolId);
        $dataTable->setClassId($classId);

        $curriculums = Curriculum::with('grade')->with('subject')->get();
        $cities = $this->getCites();
        $grades      = Grade::with('classes')->get();

        $schools = $cityId ? $this->getSchools($cityId) : null;
        $classes = $schoolId ? $this->getClasses($schoolId) : null;

        return $dataTable->render(
            'index',
            [
                'curriculums'=>$curriculums,
                'curriculumId'=>$curriculumId,
                'cities'=>$cities,
                'grades' => $grades,
                'schools' => $schools,
                'classes' => $classes,
                'cityId' => $cityId,
                'schoolId' => $schoolId,
                'classId' => $classId,
                'workbookHash' => WorkbookHashMaker::make($curriculumId)
            ]
        );
    }

    private function getCites(){
        $c = null;
//        $with = 'schools.classes.grade';
        $roler = App::make('roler');
        $role = $roler->getRole();
        if ($roler->isCityUser()){
            $c = City::where("id", "=", $role->city->id)->orderBy('order', 'asc')->get();
        }elseif ($roler->isSchoolUser()){
            $c = City::where("id", "=", $role->school->city->id)->orderBy('order', 'asc')->get();
        }else{
            $c = City::orderBy('order', 'asc')->get();
        }
        return $c;
    }

    private function getSchools($cityId){
        $s = null;
//        $with = 'city_id';
        $roler = App::make('roler');
        $role = $roler->getRole();
        if ($roler->isSchoolUser()){
            $s = School::where("id", "=", $role->school->id)->orderBy('order', 'asc')->get();
        }else{
            $s = School::where("city_id", '=', $cityId)->orderBy('order', 'asc')->get();
        }
        return $s;
    }

    private function getClasses($schoolId){
        return Klass::where('school_id', '=', $schoolId)->get();
    }

    public function getFooterData(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $prep_id = 1;
                $city_id = $request->input('city_id');
                $school_id = $request->input('school_id');
                $curri_id = $request->input('curri_id');

                $dataFooter = AggregationService::getFooterData($prep_id, $city_id, $school_id, $curri_id,false);


                $data = ["dataFooter"=>$dataFooter, 'count'=>self::createCount($city_id, $school_id)];
                $returnHTML = view('aggregation.footer',$data)->render();
                return json_encode(["status"=>'true', "data"=>$returnHTML]);
            }
        } catch (\Exception $ex){
            throw new \Exception('フッターデータの取得に失敗しました', $ex->getCode());
//            throw new \Exception('Fail to getFooterData ' . $ex->getMessage() . ' ' . $ex->getTraceAsString(), $ex->getCode());
        }
    }

    private function createCount($city_id, $school_id){
        $result = null;
        switch(AggregationService::checkMode($city_id, $school_id)){
            case AggregationDataTable::MODE_CLASSES:
                $result = 3;
                break;
            case AggregationDataTable::MODE_SCHOOLS:
                $result = 2;
                break;
            case AggregationDataTable::MODE_CITIES:
                $result = 1;
                break;
        }

        return $result;
    }

}

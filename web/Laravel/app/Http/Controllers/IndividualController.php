<?php

namespace App\Http\Controllers;

use App\City;
use App\Curriculum;
use App\DataTables\IndividualDataTable;
use App\Grade;
use App\School;
use App\Services\IndividualService;
use App\Services\WorkbookHashMaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Validator;

class IndividualController extends KdmDatatablesController
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
    public function index(IndividualDataTable $dataTable, $curriculumId = 1)
    {
        $dataTable->setCurriculum(Curriculum::find($curriculumId));
        $curriculums = Curriculum::with('grade')->with('subject')->get();
        $grades = Grade::with('classes')->get();
        $cityId = $this->getCityId();
        $schoolId = $this->getSchoolId();
        $schools = $cityId ? $this->getSchools($cityId) : null;
        return $dataTable->render(
            'index',
            [
                'curriculums' => $curriculums,
                'curriculumId'=>$curriculumId,
                'cities' => $this->getCities(),
                'schools' => $schools,
                'cityId' => $cityId,
                'schoolId' => $schoolId,
                'grades' => $grades,
                'workbookHash' => WorkbookHashMaker::make($curriculumId)
            ]
        );
    }

    private function getCityId(){
        $roler = App::make('roler');
        $role = $roler->getRole();
        if ($roler->isCityUser()){
            $cId = $role->city->id;
        }elseif ($roler->isSchoolUser()){
            $cId = $role->school->city->id;
        }else{
            $cId = null;
        }
        return $cId;
    }

    private function getSchoolId(){
        $roler = App::make('roler');
        $role = $roler->getRole();
        if ($roler->isSchoolUser()){
            $sId = $role->school->id;
        }else{
            $sId = null;
        }
        return $sId;
    }


    private function getSchools($cityId){
        $s = null;
        $roler = App::make('roler');
        $role = $roler->getRole();
        if ($roler->isSchoolUser()){
            $s = School::where("id", "=", $role->school->id)->orderBy('order', 'asc')->get();
        }else{
            $s = School::where("city_id", '=', $cityId)->orderBy('order', 'asc')->get();
        }
        return $s;
    }
    private function getCities(){
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

    public function getFooterData(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $prep_id = 1;
                $city_id = $request->input('city_id');
                $school_id = $request->input('school_id');
                $class_id = $request->input('class_id');
                $curri_id = $request->input('curri_id');

                $dataFooter = IndividualService::getFooterData($prep_id, $city_id, $school_id, $class_id, $curri_id,false);
                $returnHTML = view('individual.footer',["dataFooter"=>$dataFooter])->render();
                return json_encode(["status"=>'true', "data"=>$returnHTML]);
            }
        } catch (\Exception $ex){
            throw new \Exception('フッターデータの取得に失敗しました', $ex->getCode());
//            throw new \Exception('Fail to getFooterData ' . $ex->getMessage() . ' ' . $ex->getTraceAsString(), $ex->getCode());
        }
    }
}

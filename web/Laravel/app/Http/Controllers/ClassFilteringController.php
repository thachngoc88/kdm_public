<?php

namespace App\Http\Controllers;

use App\Klass;
use App\School;
use Illuminate\Http\Request;

class ClassFilteringController extends KdmDatatablesController
{
    public function getSchoolByCityId(Request $request, $cityId = 0){
        $query = School::with('classes.grade')->select();
        if($cityId != 0){
            $query->where('city_id','=', $cityId);
        }
        $schools = $query->orderBy('order', 'asc')->get();
        return response(['error' => false, 'data' => $schools, 'status_code' => 200]);
    }

    public function getClassBySchoolId(Request $request, $schoolId = 0){
        $query = Klass::with('grade')->select();
        if($schoolId != 0){
            $query->where('school_id','=', $schoolId);
        }
        $classes = $query->get();
        return response(['error' => false, 'data' => $classes, 'status_code' => 200]);
    }
}

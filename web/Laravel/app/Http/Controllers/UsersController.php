<?php

namespace App\Http\Controllers;
use App\City;
use App\Datatables\UsersDataTable;
use App\Prefecture;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class UsersController extends KdmDatatablesController
{
  /*  public function index()
    {

        return view('users.index');
    }*/
    public function index(UsersDataTable $dataTable)
    {
        $pre = Prefecture::with('cities.schools.classes.grade')->get();
        //app('debugbar')->warning('prefectures');
        //app('debugbar')->warning($pre);
        return $dataTable->render('index',[
            'prefectures' => $pre,
            'cityId'      => null,
            'schoolId'    => null,
            'cities'      => City::orderBy('order', 'asc')->get(),
            'schools'     => null,
        ]);


        //return $dataTable->render('users.index');
    }

    public function update(Request $request, $id)
    {
        $params = $request->only(['password', 'password_confirm', 'enabled']);
        //validation
        if (!is_null($params)) {

            $validator = Validator::make(array_filter($params), [
//                'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#@%]).*$/',
                'password' => '',
                'password_confirm' => 'same:password',
                'enabled' => 'boolean',
            ]);
            if ($validator->fails()) {
                return response([
                    'error' => true,
                    'data' => "ユーザデータに不正な値が入力されました",
                    'status_code' => 422
                ]);
            }
        }

        $password = $params['password'];
        $enabled = $params['enabled'];

        //end
        DB::beginTransaction();
        try {
            $user = User::find($id);
            if($user){
                $data = ['enabled' => $enabled];
                if(!empty($password)){
                    $data['password'] = Hash::make($password);
                }
                $user->fill($data)->save();
            }
            DB::commit();
            return response([
                'error' => false,
                'data' => $user,
                'status_code' => 200
            ]);
        }
        catch(\Exception $e) {
            DB::rollback();
            throw new \Exception('ユーザ情報の更新に失敗しました', 500);
        }
        return response([
            'error' => false,
            'data' => $user,
            'status_code' => 200
        ]);
    }
}

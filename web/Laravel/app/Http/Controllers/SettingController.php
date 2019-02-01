<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
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
    public function index()
    {
        return view('setting');
    }


    public function  save(Request $request){
        $params = $request->only(['id','name']);
        set_time_limit(6000);
        $exitCode = Artisan::call('pdf');


        return response([
            'error' => false,
            'data' => [$exitCode],
            'status_code' => 200
        ]);
    }
}

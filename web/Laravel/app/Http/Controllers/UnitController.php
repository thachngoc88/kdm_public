<?php

namespace App\Http\Controllers;

use App\Unit;

class UnitController extends Controller
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
    public function index($unitId)
    {
        $unit = Unit::find($unitId);
        return view('unit', ['unit' => $unit]);
    }
}

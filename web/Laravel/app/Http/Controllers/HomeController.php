<?php

namespace App\Http\Controllers;

use App\Roler;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
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
    public function index($action = null)
    {
        $roler = App::make('roler');
        $actionPrefix = $action ?: ($roler->getRoleKey() === \App\Services\Roler::KEY_CHALLENGE) ? 'challenge' : 'admin';
        return view($actionPrefix . 'home', [
            'role' => $roler->getRole()
        ]);
    }
}

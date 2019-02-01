<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class ErrorsController extends Controller {
    

    public function error403() {
        app('debugbar')->info("handle 222233333333333");
        return view('errors.403');
    }
}


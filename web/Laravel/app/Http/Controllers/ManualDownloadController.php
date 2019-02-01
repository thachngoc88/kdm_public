<?php

namespace App\Http\Controllers;

use App\Services\Utils;
use Illuminate\Http\Request;

class ManualDownloadController extends Controller
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
    public function index($type)
    {
        $fileName = '';

        switch($type){
            case 'school':
                $fileName = 'H30学校用マニュアル';
                break;
            case 'challengeuser':
                $fileName = 'H30児童用マニュアル';
                break;
            case 'relation':
                $fileName = 'H30チャレンジ補充問題関連付け';
                break;
            default:
                abort(403);
        }
        $path = Utils::getPathName();
        return response()->download($path . $fileName . '.pdf');
    }
}

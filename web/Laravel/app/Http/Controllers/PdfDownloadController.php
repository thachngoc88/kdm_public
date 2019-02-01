<?php

namespace App\Http\Controllers;

use App\Services\Utils;
use App\Workbook;
use Illuminate\Support\Facades\Cookie;

class PdfDownloadController extends Controller
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
    public function index($type, $workbookId)
    {
        if($type == 'q'){
            Cookie::queue(Cookie::make('challengeUserStatus', 'download'));
        }
        $workbook = Workbook::find($workbookId);
        $path = Utils::getPathName();
        $fileName = Utils::getFileName($workbook, $type);
        return response()->download($path.$fileName);
    }


}

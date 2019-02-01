<?php

namespace App\Http\Controllers;

class BackupDownloadController extends Controller
{

    private static $path;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        self::$path = $this->getPathName();
        $this->middleware('auth');
    }

    /**
     * @param int $count
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function index($count = 0)
    {
        try{
            return response()->download(self::$path.$this->getFileName($count));
        }catch (\Exception $ex){
            echo ($ex->getTraceAsString());
        }
    }

    private function getFileName($count){
        $files_in_dir = scandir(self::$path);
        if(isset($files_in_dir)){
            $files_dump  = preg_grep('/(\d{4})-(\d{2})-(\d{2})/', $files_in_dir);
            $files_dump = array_reverse($files_dump);
            return $files_dump[$count];
        }
    }

    private function getPathName(){
        return storage_path("app/dbdump/");
    }
}

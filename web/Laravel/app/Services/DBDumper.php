<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DBDumper
{

    const MAX_FILE = 5;
    const BACKUP_DIR = "storage/app/dbdump/";

    public static function dump(){

        $date = date('Y-m-d-H-i');
        Log::info("Begin dump database at " .$date);
        try {
            Artisan::call('db:backup',[
                '--database'=>'mysql',
                '--destination'=>'local',
                '--destinationPath'=>'dbdump/',
                '--timestamp'=>'Y-m-d-H-i-s',
                '--compression'=>'gzip',
            ]);

            $files_in_dir = scandir(Self::BACKUP_DIR);
            if(isset($files_in_dir)){
                $files_dump  = preg_grep('/(\d{4})-(\d{2})-(\d{2})/', $files_in_dir);
                $files_dump = array_reverse($files_dump);
                if(!empty($files_dump) && count($files_dump) > Self::MAX_FILE){
                    $files_delete = array_splice($files_dump,Self::MAX_FILE);
                    foreach ($files_delete as $file){
                        Log::info("Delete dump file : ".Self::BACKUP_DIR.$file);
                        unlink(Self::BACKUP_DIR.$file);
                    }
                }
            }
        }catch (\Exception $e) {
            throw new \Exception('Fail to mark ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        Log::info("End dump database at " .$date);

    }

}

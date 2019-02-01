<?php

namespace App\Services;

use App\ChallengeUser;
use App\Mark;
use App\MarkingLog;
use App\Record;
use App\Workbook;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Marker
{

    public static function mark($force = false){
        $timeStart = Carbon::now();

        $ml = MarkingLog::latest('updated_at')->select("updated_at")->first();
        $record = Record::latest('updated_at')->select("updated_at")->first();

        if($force || (isset($ml) && isset($record) && ($record->updated_at > $ml->updated_at))){
            echo("Begin execute maker !!!");
            try {
                $log = new MarkingLog();
                $log->save();

//            $this->updateMarks($log);

                DB::beginTransaction();


                PassingRateUpdater::updatePassingRates($log);
                AggregationUpdater::updateAggregationCurriculums($log);


                //IndividualUpdater::updateIndividualCurriculums($log);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
//            throw new \Exception('Fail to mark ' . $e->getMessage() . ' ');
                throw new \Exception('Fail to mark ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            }

            echo("Handling is done. Time(sec): " . $timeStart->diffInSeconds(Carbon::now()) . PHP_EOL);
        } else {
            echo("Not execute maker !!!");
        }


    }

}

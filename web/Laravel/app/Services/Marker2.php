<?php

namespace App\Services;

use App\ChallengeUser;
use App\Mark;
use App\MarkingLog;
use App\Record;
use App\Workbook;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Marker2
{

    public static function mark($force = false){
        $timeStart = Carbon::now();

        Log::info("Before run Marker2..................");

        $ml = MarkingLog::latest('updated_at')->select("updated_at")->first();
        $record = Record::latest('updated_at')->select("updated_at")->first();

        if($force || (isset($ml) && isset($record) && ($record->updated_at > $ml->updated_at))){
            echo("Begin execute maker 22222!!!");
            Log::info("Begin execute maker 2 !!!");
            try {
                $log = new MarkingLog();
                $log->save();

//            $this->updateMarks($log);

                DB::beginTransaction();

                //PassingRateUpdater::updatePassingRates($log);
                //AggregationUpdater::updateAggregationCurriculums($log);
                IndividualUpdater::updateIndividualCurriculums($log);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
//            throw new \Exception('Fail to mark ' . $e->getMessage() . ' ');
                throw new \Exception('Fail to mark ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            }

            echo("Handling is done. Time(sec): " . $timeStart->diffInSeconds(Carbon::now()) . PHP_EOL);
            Log::info("Handling mark 2 is done. Time(sec): " . $timeStart->diffInSeconds(Carbon::now()));
        } else {
            echo("Not execute maker !!!");
            Log::info("Not execute maker !!!");
        }


    }




    // mod sada 2017-10-24
    private function updateMarks($log){
        $gradeNumbers = Grade::get()->pluck('number');
        foreach($gradeNumbers as $gradeNumber){
            echo("-updateMarks- Grand number: {$gradeNumber} - marking" . PHP_EOL);

            $challengeUsers = $this->getChallengeUsersOfGradeNumber($gradeNumber);
            echo("ChallengeUsers size: " .count($challengeUsers) . PHP_EOL);

            $workbooks = $this->getWorkbooksOfGradeNumber($gradeNumber);
            echo("Workbooks size: " .count($workbooks) . PHP_EOL);

            $max = 3; // TODO just 3 count because heavy loop
            foreach($challengeUsers as $challengeUser){
                foreach($workbooks as $workbook){
                    $this->saveMark($challengeUser, $workbook, $log);
                    echo("saved Mark Workbook ID: {$workbook->id} Grand number: {$gradeNumber}" . PHP_EOL);
                }
                if(--$max <= 0){
                    break;
                }
            }
            break;
        }
    }

    private function saveMark(ChallengeUser $challengeUser, Workbook $workbook, MarkingLog $log){
        $mark = Mark::firstOrNew([
            'challenge_user_id' => $challengeUser->id,
            'workbook_id' => $workbook->id,
        ]);

        $mark = $this->fillMark($mark, $challengeUser, $workbook);
        $mark->marking_log_id = $log->id;
        $mark->save();
    }

    private function fillMark(Mark $mark, ChallengeUser $challengeUser, Workbook $workbook){
        $records = Record::
        join('questions as Q','records.question_id','Q.id')
            ->join('challenge_users as CU','records.challenge_user_id','CU.id')
            ->where('Q.workbook_id', '=', $workbook->id)
            ->where('CU.id', '=', $challengeUser->id)
            ->whereNull('Q.deleted_at')
            ->whereNull('CU.deleted_at')
            ->get();

//        $records = Record::where('challenge_user_id', '=', $challengeUser->id)
//                    ->whereHas('question' ,function($query) use ($challengeUser,$workbook){
//                        $query->where('workbook_id', '=', $workbook->id);
//                    })
//                    ->get();
        $totalCount = $records->count();
        $passCount = $records->whereIn('record', 1)->count();
        $failureCount = $records->where('record','=', null)->count();

        $mark->mark = $totalCount > 0 ? (($passCount / $totalCount) * 100) : 0;
        $mark->started = $failureCount != $totalCount ? 1 : 0;

        return $mark;
    }

}

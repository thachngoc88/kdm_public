<?php

namespace App\Http\Controllers;

use App\ChallengeUserMapsheetAccess;
use App\ChallengeUserUnitStatus;
use App\ChallengeUserWorkbookStatus;
use App\Curriculum;
use App\Timing;
use Carbon\Carbon;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MapSheetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected  $curriculum;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $curriculumId)
    {
        $curriculum = Curriculum::find($curriculumId);

        if(App::make('roler')->isChallengeUser()) {
            $this->logAccess($curriculum);
        }

        return view('mapsheet', [
            //'message' => $this->createMessage($request, $curriculum),
            'message' => $this->createMessage($request, Curriculum::find(1)), // modify that curriculum set ID:1 force
            'curriculum' => $curriculum,
            'stamps' => $this->createStamps($curriculum),
            'marks' => $this->createMarks($curriculum)
        ]);
    }

    private function logAccess($curriculum){
        $cu_id = $this->getChallengeUser()->id;
        $curriculum_id = $curriculum->id;
        if($this->isFirstAccessInDay($cu_id, $curriculum_id)){
            ChallengeUserMapsheetAccess::create(['challenge_user_id'=>$cu_id,'curriculum_id' => $curriculum_id]);
        }
    }

    private function isFirstAccessInDay($cu_id, $curriculum_id){
        $challengeUserMapsheetAccess = ChallengeUserMapsheetAccess::where([
            'challenge_user_id'=>$cu_id,
            'curriculum_id' => $curriculum_id,
        ])->latest('updated_at')->select("updated_at")->first();

        if(!isset($challengeUserMapsheetAccess)){
            return true;
        } else {
            $last = new Carbon($challengeUserMapsheetAccess->updated_at);
            return !$last->isSameDay(Carbon::now());
        }
    }

    private function createStamps($curriculum){
        $stamps = [];
        foreach ($curriculum->units as $unit) {
            $stamps[$unit->id] = $this->getStampNumber($unit);
        }
        return $stamps;
    }

    private function getStampNumber($unit){
        $cu = $this->getChallengeUser();
        if(isset($cu)){
            return ChallengeUserUnitStatus::where(['challenge_user_id' => $cu->id, 'unit_id' => $unit->id])->first()->status;
        }else{
            return 0;
        }
    }
    private function createMarks($curriculum){
        $marks = [];
        foreach ($curriculum->units as $unit) {
            foreach ($unit->workbooks as $workbook) {
                $marks[$workbook->id] = $this->getMarkNumber($workbook);
            }
        }
        return $marks;
    }
    private function getMarkNumber($workbook){
        $cu = $this->getChallengeUser();
        if(isset($cu)) {
            return ChallengeUserWorkbookStatus::where(['challenge_user_id' => $cu->id, 'workbook_id' => $workbook->id])->first()->status;
        }
        else
            return 0;
    }

    private function createMessage(Request $request, Curriculum $curriculum){
        $challengeUserStatus = $request->cookie('challengeUserStatus') ?: 'login';
        $timing = $this->createTimingByStatus($curriculum, $challengeUserStatus);

        $message = $this->createMessageByStatus($timing, $challengeUserStatus);
        return $message;
    }

    private function createTimingByStatus(Curriculum $curriculum, $challengeUserStatus){
        $timing = $curriculum->timings->where('code', preg_replace('@^(.+)?-.*@', '$1', $challengeUserStatus))->first();
        return $timing;
    }

    private function createMessageByStatus(Timing $timing, $challengeUserStatus){
//        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $conditions = $timing->conditions;
        $messages = null;
            switch ($timing->code){
                case 'login':
                    app('debugbar')->info("login timing");
                    $now  = date('H:i:s');
                    foreach($conditions as $condition) {
                    $fromtime = date('H:i:s', strtotime($condition->time_from));
                    $untiltime = date('H:i:s', strtotime($condition->time_until));
                        if (is_null($condition->time_from) || is_null($condition->time_until)) {
                            $condition->messages; // TODO
                        } elseif ($now >= $fromtime && $now <= $untiltime) {
                            $messages = $condition->messages;
                            break 2;
                        }
                        elseif($now <= $untiltime && $untiltime <= date('H:i:s',strtotime("12:00:00")) && $fromtime > date('H:i:s', strtotime("12:00:00"))){
                            $messages = $condition->messages;
                            break 2;
                        }
                    }
                    break;
                case 'download':
                    app('debugbar')->info("download timing");
                    $now  = date('H:i:s');
                    foreach($conditions as $condition) {
                        $fromtime = date('H:i:s', strtotime($condition->time_from));
                        $untiltime = date('H:i:s', strtotime($condition->time_until));
                        if (is_null($condition->time_from) || is_null($condition->time_until)) {
                            $condition->messages; // TODO
                        } elseif ($now >= $fromtime && $now <= $untiltime) {
                            $messages = $condition->messages;
                            break 2;
                        }
                        elseif($now <= $untiltime && $untiltime <= date('H:i:s',strtotime("12:00:00")) && $fromtime > date('H:i:s', strtotime("12:00:00"))){
                            $messages = $condition->messages;
                            break 2;
                        }
                    }
                    break;
                case 'input':
                    app('debugbar')->info("input timing");
                    $condition = $this->getCondition($timing,$challengeUserStatus);
                    $messages = $condition->messages;
                    break;
        }
        if(!is_null($messages) && isset($messages)) {
            return $messages[rand(0, 2)];
        }
        else return null;
    }

    private function getCondition($timing,$challengeUserStatus){
        $status = preg_replace('@^input-(.+)$@', '$1', $challengeUserStatus);
        $condition = null;
        switch ($status){
            case 'curriculum':
                $condition = $timing->conditions[0];
                break;
            case 'unit':
                $condition = $timing->conditions[1];
                break;
            case 'workbook':
                $condition = $timing->conditions[2];
                break;
            case 'notnull':
                $condition = $timing->conditions[3];
                break;
            case 'null':
                $condition = $timing->conditions[4];
                break;
        }
        return $condition;
     }
    private function getChallengeUser(){
        $roler = App::make('roler');
        $cu = null;
        if($roler->isChallengeUser()) {
            $cu = $roler->getRole();
        }
        return $cu;
    }

}

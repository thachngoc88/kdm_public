<?php

namespace App\Http\Controllers;

use App\ChallengeQuestionSupplement;
use App\ChallengeUser;
use App\ChallengeUserUnitStatus;
use App\ChallengeUserWorkbookStatus;
use App\ChallengeUserWorkbookUpdateCount;
use App\Record;
use App\FirstRecord;
use App\Workbook;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Validator;

class RecordInputController extends Controller

{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $workbook_id;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($workbookId)
    {
        $roler = App::make('roler');
        if($roler->isChallengeUser()) {

            $cu = $roler->getRole();
            $wb = Workbook::with(['questions'=>function($q) use ($cu) {
                $q->with([
                    'records' => function($q) use ($cu) {
                        $q
                            ->where('challenge_user_id', '=', $cu->id)
                            ->select('*');
                    }
                ])
                    ->select('*');

            }]);
            $wb = $wb->find($workbookId);
            $data = [
                'workbook'      => $wb,
                'challengeUser' => $cu
            ];
            return view('recordinput', $data);

        }else{
//            return "User is not challenge user";
            return response('ログイン中のユーザは、チャレンジユーザではありません', 401);;
        }
    }

    private function varidationForSaving(Request $request){
        $params = $request->only(['workid','userid', 'cu', 'records']);
        if (!is_null($params['records'])) {
            foreach ($params['records'] as $key => $value) {
                $validator = Validator::make(array_filter($value), [
                    'answer' => [
                        'required',
                        Rule::in(['none', 'correct', 'incorrect']),
                    ]
                ]);

                if ($validator->fails()) {
                    return response([
                        'error' => true,
                        'data' => "不正なリクエストです",
                        'status_code' => 422
                    ]);
                }
            }
        }
    }

    public function  save(Request $request){
        $params = $request->only(['workid','userid', 'cu', 'records']);

        //validation
        if (!is_null($params['records'])) {
            foreach ($params['records'] as $key => $value) {
                $validator = Validator::make(array_filter($value), [
                    'answer' => [
                        'required',
                        Rule::in(['none', 'correct', 'incorrect']),
                    ]
                ]);

                if ($validator->fails()) {
                    return response([
                        'error' => true,
                        'data' => "不正なリクエストです",
                        'status_code' => 422
                    ]);
                }
            }
        }
        //end

        DB::beginTransaction();

        try {

            $output = [];
            $output = $this->updateRecord($params, $output);
            $this->updateChallengeUserWorkbookStatuses($params, $output);

            $cuid = $params['cu'];
            $wbid = $params['workid'];

            $isFirstDoWorkbook = $this->isFirstDoWorkbook($cuid, $wbid);
            if($isFirstDoWorkbook){
                $this->insertFirstRecord($output);
            }
            $this->updateChallengeUserWorkbookUpdateCount($cuid, $wbid, $isFirstDoWorkbook);

            DB::commit();


            $this->setWorkBookId($wbid);
            $this->updateCookie();

            return response([
                'error' => false,
                'data' => $output,
                'status_code' => 200
            ]);

        }
        catch(\Exception $e) {
            DB::rollback();
            throw new \Exception('成績データの更新に失敗しました '.$e->getMessage() .$e->getTraceAsString(), 500);
//            throw new \Exception('Fail to insert data:'.$e->getMessage() .$e->getTraceAsString(), 500);
        }
    }
    private function isFirstDoWorkbook($challenge_user_id, $workbook_id)
    {
        return ChallengeUserWorkbookUpdateCount::where('challenge_user_id', $challenge_user_id)->where('workbook_id', $workbook_id)->count() === 0;
    }

    private function insertFirstRecord($outputs)
    {
        foreach ($outputs as $o){
            FirstRecord::create(
                [
                    'record' => $o->record,
                    'challenge_user_id' => $o->challenge_user_id,
                    'question_id' => $o->question_id,
                ]
            );
        }
    }

    private function updateChallengeUserWorkbookUpdateCount($challenge_user_id, $workbook_id, $isFirstDoWorkbook)
    {
        if(!$isFirstDoWorkbook){
            ChallengeUserWorkbookUpdateCount::where(['challenge_user_id' => $challenge_user_id, 'workbook_id' => $workbook_id])
                ->first()
                ->increment('count',1);
        } else {
            ChallengeUserWorkbookUpdateCount::create(
                [
                    'challenge_user_id' => $challenge_user_id,
                    'workbook_id' => $workbook_id,
                    'count' => 1,
                ]
            );
        }
    }


    /**
     * @param $params
     * @param $output
     * @return array
     */
    private function updateRecord($params, $output)
    {
        foreach ($params['records'] as $key => $value) {
            $question = $value['question'];
            $answer = $value['answer'];
            $record = Record::where(['challenge_user_id' => $params['cu'], 'question_id' => $question])->first();
            if (!isset($record)) {
                $record = new Record();
            }
            switch ($answer) {
                case 'none':
                    $record->record = null;
                    break;
                case 'correct':
                    $record->record = 1;
                    break;
                case 'incorrect':
                    $record->record = 2;
                    break;
                default:
                    $this->throwValidationException();
            }

            $record->challenge_user_id = $params['cu'];
            $record->question_id = $question;
            $record->save();

            $output[] = $record;
        }
        return $output;
    }

    /**
     * @param $params
     * @param $output
     * @return array
     */
    private function updateChallengeUserWorkbookStatuses($params, $output)
    {
        $wb_id = $params['workid'];
        $cu_id = $params['cu'];

        $wb = Workbook::find($wb_id);
        $isChallenge = $this->isChallenge($wb);

        $wb_status = $this->getWbStatus($output, $isChallenge);
        $this->insertOrUpdateWorkbookStatus($cu_id, $wb_id, $wb_status);

        if($isChallenge){
            if($wb_status == 3 || $wb_status == 0){
                $un_id = $wb->unit_id;
                $this->updateAllWorkbookStatus($cu_id, $un_id,$wb_status);
            } else if($wb_status == 1 || $wb_status == 2){
                $challenge = $wb->challenge()->first();
                $challenge_id = $challenge->id;
                $list_wb_supplement = $this->getAllWorkbookSupplementOfChallenge($challenge_id);
                $list_cqs = $this->getRecordOfChallenge($challenge_id, $cu_id);

                foreach ($list_wb_supplement as $wb_supplement) {
                    $wb_id_supplement = $wb_supplement->id;
                    $wb_status_update = $this->getWbStatusBySupplement($wb_id_supplement, $list_cqs);
                    $this->insertOrUpdateWorkbookStatus($cu_id, $wb_id_supplement, $wb_status_update);
                }
            }
        }

        //Update UnitStatus -------------
        $this->updateChallengeUserUnitStatuses($params, $isChallenge, $wb_status);

        return $wb_status;
    }

    /**
     * @param $params
     * @param $isChallenge
     * @param $wb_status
     * @return array
     */
    private function updateChallengeUserUnitStatuses($params, $isChallenge, $wb_status)
    {
        $cu_id = $params['cu'];
        $wb_id = $params['workid'];
        $wbById= Workbook::find($wb_id);
        $un_id = $wbById->unit_id;
        $un_status = 2;

        if($isChallenge && $wb_status == 3){
            $un_status = 3;
        } else if($isChallenge && $wb_status == 0){
            $un_status = 0;
        } else {
            $count_status_3 = 0;
            $count_status_2 = 0;
            $count_status_1 = 0;
            $count_status_0 = 0;
            $status_challenge = 0;

            $cuws_list = $this->getAllChallengeUserWorkbookStatusByUnit($cu_id, $un_id);

            foreach ($cuws_list as $cuws){
                $status = $cuws->status;
                $wb = $cuws->workbook()->first();
                if($this->isChallenge($wb)){
                    $status_challenge = $status;
                }else {
                    switch($status) {
                        case 3:
                            $count_status_3++;
                            break;
                        case 2:
                            $count_status_2++;
                            break;
                        case 1:
                            $count_status_1++;
                            break;
                        case 0:
                            $count_status_0++;
                            break;
                        default:
                            break;
                    }
                }

            }

            if($status_challenge == 3){
                if($count_status_1 > 0){
                    $un_status = 1;
                } else {
                    $un_status = 3;
                }
            } else {
                $count_wb = count($cuws_list) - 1;
                if($count_wb == $count_status_3){
                    $un_status = 3;
                } else if($count_wb == $count_status_2){
                    $un_status = 2;
                }if($count_wb == $count_status_0){
                    $un_status = 0;
                } else if($count_status_1 > 0 || $count_status_0 > 0){
                    $un_status = 1;
                }
            }
        }

        $this->insertOrUpdateChallengeStatus($cu_id, $un_id, $un_status);
        $this->insertOrUpdateUnitStatus($cu_id, $un_id, $un_status);
    }

    private function getAllChallengeUserWorkbookStatusByUnit($cu_id, $un_id){
        return ChallengeUserWorkbookStatus::whereHas('workbook.unit',function ($q) use($un_id){
            $q->where('units.id','=', $un_id);
            //$q->where('workbooks.number','!=', 0);
        })
            ->where('challenge_user_id',$cu_id )
            ->get();
    }

    private function isChallenge($wb){
        return $wb->number == 0 ? true : false;
    }

    private function insertOrUpdateWorkbookStatus($cu_id, $wb_id, $wb_status){
        $cuws = ChallengeUserWorkbookStatus::firstOrNew([
            'challenge_user_id' => $cu_id,
            'workbook_id' => $wb_id
        ]);
        $cuws->status = $wb_status;
        $cuws->save();
    }

    private function updateAllWorkbookStatus($cu_id, $un_id, $wb_status){
        ChallengeUserWorkbookStatus::whereHas('workbook.unit',function ($q) use($un_id){
            $q->where('units.id','=', $un_id);
        })
            ->where('challenge_user_id',$cu_id )
            ->update(['status' => $wb_status]);
    }

    private function insertOrUpdateChallengeStatus($cu_id, $un_id, $un_status){
        $challenge = ChallengeUserWorkbookStatus::whereHas('workbook.unit',function ($q) use($un_id){
            $q->where('units.id','=', $un_id);
            $q->where('workbooks.number','=', 0);
        })
            ->where('challenge_user_id',$cu_id )
            ->first();
        $this->insertOrUpdateWorkbookStatus($cu_id, $challenge->workbook_id, $un_status);
    }

    private function insertOrUpdateUnitStatus($cu_id, $un_id, $un_status){
        $cuus = ChallengeUserUnitStatus::firstOrNew([
            'challenge_user_id' => $cu_id,
            'unit_id' => $un_id
        ]);
        $cuus->status = $un_status;
        $cuus->save();
    }

    private function getWbStatusBySupplement($wb_id_supplement, $list_cqs){
        $wb_status = 0;
        $countRecord = 0;
        $countPass=0;
        foreach ($list_cqs as $cqs) {
            $supp_wb_id = $cqs->supp_wb_id;
            if($wb_id_supplement == $supp_wb_id){
                $countRecord++;
                if($cqs->record == 1){
                    $countPass++;
                }
            }
        }
        if($countRecord != 0){
            $wb_status = ($countPass * 100 / $countRecord) == 100 ? 3 : 0;
        }
        return $wb_status;
    }

    private function getWbStatus($records, $isChallenge){
        $wb_status = 1;
        $isNothing = true;
        $countPass = 0;
        foreach ($records as $record){
            if($record->record == 1){
                $countPass++;
            }
            if($record->record != null){
                $isNothing = false;
            }
        }

        if($isNothing){
            $wb_status = 0;
        } else {
            $mark = $countPass * 100 / count($records);
            if($mark >= 80 ){
                $wb_status = 2;
            } else {
                $wb_status = 1;
            }
            if($isChallenge && $mark== 100){
                $wb_status = 3;
            }
        }
        return $wb_status;
    }

    private function getAllWorkbookSupplementOfChallenge($challenge_id){
        return Workbook::whereHas('supplement.challenge_questions_supplenments.challenge', function ($q) use($challenge_id){
            $q->where('challenges.id',$challenge_id);
        })->get();
    }

    private function getRecordOfChallenge($challenge_id,$cu_id){
        return ChallengeQuestionSupplement
            ::leftjoin('questions as Q','challenge_questions_supplements.question_id','Q.id')
            ->leftjoin('records as R','Q.id','R.question_id')
            ->leftjoin('supplements as S','challenge_questions_supplements.supplement_id','S.id')
            ->where('R.challenge_user_id',$cu_id)
            ->where('challenge_questions_supplements.challenge_id',$challenge_id)
            ->whereNull('Q.deleted_at')
            ->whereNull('R.deleted_at')
            ->whereNull('S.deleted_at')
            ->selectRaw('S.workbook_id as supp_wb_id, R.record as record')
//            ->selectRaw('*')
            ->get();

    }

    private function setWorkBookId($workbook_id){
        $this->workbook_id = $workbook_id;
    }
    private function getWorkBookId(){
        return $this->workbook_id;
    }
    private function  updateCookie()
    {
        Cookie::queue(Cookie::make('challengeUserStatus', $this->getChallengeUserInputStatus()));
    }

    private function  getChallengeUserInputStatus(){
        $status = $this->getInputStatus();
        return "input-{$status}";
    }

    private function  getInputStatus(){
        $status = null;

        if($this->isCurriculumStatus()) {
            $status = "curriculum";
        }elseif($this->isUnitStatus()){
            $status = "unit";
        }elseif($this->isWorkbookStatus()){
            $status = "workbook";
        }elseif($this->isWorkNotnullStatus()){
            $status = "notnull";
        }else{
            $status = "null";
        }

        return $status;
    }

    private function  isCurriculumStatus(){
        $workbook_id = $this->getWorkBookId();
        $workbook = Workbook::find($workbook_id);
        $unit = $workbook->unit;
        $curriculum = $unit->curriculum;
        foreach ($curriculum->units as $unit){
            if(!$this->isEachUnitStatus($unit))
                return false;
        }
        return true;
    }

    private function  isUnitStatus(){
        $workbook_id = $this->getWorkBookId();
        $workbook = Workbook::find($workbook_id);
        $unit = $workbook->unit;
        return $this->isEachUnitStatus($unit);
    }

    private function  isEachUnitStatus($unit){
        foreach ($unit->workbooks as $workbook){
            if(!$this->isEachWorkbookStatus($workbook->id))
                return false;
        }
        return true;
    }
    private function  isWorkbookStatus(){
        $workbook_id = $this->getWorkBookId();
        return $this->isEachWorkbookStatus($workbook_id);

    }
    private function isEachWorkbookStatus($wbid){
        $cu = $this->getChallengeUser();
        $records = $this->getRecords($cu,$wbid);
        $totalCount = $records->count();
        $passCount = $records->where('record', 1)->count();
        if($totalCount != 0 && $totalCount == $passCount)
            return true;
        else
            return false;
    }
    private function  isWorkNotnullStatus(){
        $workbook_id = $this->getWorkBookId();
        $cu = $this->getChallengeUser();
        $records = $this->getRecords($cu,$workbook_id);
        $totalCount = $records->count();
        $failureCount = $records->where('record','=', null)->count();
        if($totalCount == 0 || $failureCount == $totalCount)
            return false;
        else
            return true;
    }
    private function getChallengeUser(){
        $roler = App::make('roler');
        $cu = null;
        if($roler->isChallengeUser()) {
            $cu = $roler->getRole();
        }
        return $cu;
    }
    private function getRecords(ChallengeUser $cu, $wbid)
    {
        $records = Record::
        join('questions as Q','records.question_id','Q.id')
            ->join('challenge_users as CU','records.challenge_user_id','CU.id')
            ->where('Q.workbook_id', '=', $wbid)
            ->where('CU.id', '=', $cu->id)
            ->whereNull('Q.deleted_at')
            ->whereNull('CU.deleted_at')
            ->get();
        return $records;
    }
}

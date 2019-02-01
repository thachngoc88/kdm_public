<?php

namespace App\Services;

use App\ChallengeUser;
use App\ChallengeUserUnitStatus;
use App\ChallengeUserWorkbookStatus;
use App\Curriculum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class IndividualUpdater
{
    public static function updateIndividualCurriculums($log){
        $curriculums = Curriculum::with('units.workbooks')->get();
        foreach($curriculums as $curriculum){
            echo("Curriculum id:".$curriculum->id .PHP_EOL);
            $tableName = "individual_curriculum_{$curriculum->id}";
            self::dropIndividualCurriculums($tableName);
            self::createIndividualCurriculums($tableName, $curriculum);
            self::insertDataIndividualCurriculums_main($tableName, $curriculum, $log);
        }
    }

    private static function dropIndividualCurriculums($tableName){
        Schema::dropIfExists("{$tableName}_main");
    }

    private static function createIndividualCurriculums($tableName, $curriculum){
        Schema::create("{$tableName}_main", function (Blueprint $table) use($curriculum)
        {
            $table->increments('id');
            $table->text('login_id');
            // for filtering
            $table->integer('class_id');
            $table->integer('school_id');
            $table->integer('city_id');

            foreach($curriculum->units as $unit){
                foreach($unit->workbooks as $wb){
                    $colName = "u_{$unit->number}_w_{$wb->number}";
                    $table->text(''.$colName)->nullable();
                }
            }
            $table->integer('count_c');
            $table->integer('count_p');
            $table->integer('count_f');
            $table->integer('count_nys');
            $table->text('total_pass');
            $table->text('rate_imp');
            $table->integer('marking_log_id')->unsigned();
            $table->integer('order_number')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('marking_log_id')->references('id')->on('marking_logs');
        });
    }

    private static function insertDataIndividualCurriculums_main($tableName, $curriculum, $log){
        $curriculumId = $curriculum->id;
        //Get all user by $curriculum
        $usersByCurriculumId = self::getUsersOfCurriculum($curriculumId);
        echo("Total User:".count($usersByCurriculumId) .PHP_EOL);
        $max= 2;

        $units = $curriculum->units;
        $data_tmp = array();
        foreach($units as $unit){
            $wbs = $unit->workbooks;
            foreach($wbs as $wb){
                $wbid = $wb->id;
                $colName = "u_{$unit->number}_w_{$wb->number}";
                $data_tmp[$colName]=$wbid;
            }
        }

        //dump($data_tmp);

        foreach ($usersByCurriculumId as $user){
            $max--;
            $userId = $user->id;
            $class_id = $user->class_id;
            $school_id = $user->school_id;
            $city_id = $user->city_id;

            $statusOfWorkbooks = self::getStatusOfWorkbookByUser($userId,$curriculumId);
            //$statusOfUnits = self::getStatusOfUnitByUser($userId, $curriculumId);
            $data = array();

            echo("user id: {$user->id}" . " Class_id = ".$class_id . " school_id = ".$school_id . " city_id = ".$city_id .PHP_EOL);

//            foreach($curriculum->units as $unit){
//                foreach($unit->workbooks as $wb){
//                    $wbid = $wb->id;
//                    $colName = "u_{$unit->number}_w_{$wb->number}";
//                    if(count($statusOfWorkbooks) == 0){
//                        $data[$colName]=UpdaterTool::NYS;
//                    } else {
//                        foreach($statusOfWorkbooks as $statusOfWorkbook){
//                            $status_wb_id = $statusOfWorkbook->workbook_id;
//                            if($status_wb_id == $wbid){
//                                $status = $statusOfWorkbook->status;
//                                echo('status:'. $status);
//                                $data[$colName]=self::createSymbolIndividualCurriculums_main($status);
//                                break;
//                            }
//                        }
//                    }
//                }
//            }

//            foreach($data_tmp as $key => $value){
//                if(count($statusOfWorkbooks) == 0){
//                    $data[$key]=UpdaterTool::NYS;
//                } else {
//                    foreach($statusOfWorkbooks as $statusOfWorkbook){
//                        $status_wb_id = $statusOfWorkbook->workbook_id;
//                        if($status_wb_id == $value){
//                            $status = $statusOfWorkbook->status;
//                            $data[$key]=self::createSymbolIndividualCurriculums_main($status);
//                            break;
//                        }
//                    }
//                }
//            }


            if(count($statusOfWorkbooks) == 0){
                foreach($data_tmp as $key => $value){
                    $data[$key]=UpdaterTool::NYS;
                }
            } else {
                $count = 0;

                foreach($statusOfWorkbooks as $statusOfWorkbook){
                    $status = $statusOfWorkbook->status;
                    $data[$statusOfWorkbook->colName] = self::createSymbolIndividualCurriculums_main($status);
                    $count++;
                }
            }

            //dump($data);
            //break;

            $data['login_id'] = $user->login_id;
            $data['order_number'] = $user->order_number + ($class_id * 100);
            $data['marking_log_id'] = $log->id;

            $data['class_id'] = $class_id;
            $data['school_id'] = $school_id;
            $data['city_id'] = $city_id;

            $countValue = array_count_values($data);

            $data['count_c']=self::countSymbol($countValue,UpdaterTool::PASS_CHL);
            $data['count_p']=self::countSymbol($countValue,UpdaterTool::PASS);
            $data['count_f']=self::countSymbol($countValue,UpdaterTool::FAIL);
            $data['count_nys']=self::countSymbol($countValue,UpdaterTool::NYS);
            $data['total_pass']=($data['count_c'] + $data['count_p']) . " (" . $data['count_c'] . ")";

            $passAndChallenge = $data['count_c']+$data['count_p'];
            $total = $passAndChallenge + $data['count_f'] + $data['count_nys'];

//            $rate = ($total==0) ? 0 : $passAndChallenge / $total * 100;
//            $data['rate_imp'] = sprintf('%.1f', $rate) . "%";
            $rate = ($total==0) ? 0 : $passAndChallenge / $total;
            $data['rate_imp'] = UpdaterTool::createPercentSpan($rate);

            DB::table("{$tableName}_main")->insert($data);
            //if($max == 0) break;
        }
    }

    private static function countSymbol($countValue, $key){
        try{
            if (isset($countValue["".$key])) {
                $count = $countValue["".$key];
                return $count;
            }
            return 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private static function createSymbolIndividualCurriculums_main($status){
        $symbolRst = UpdaterTool::NYS;
        switch ($status){
            case 0:
                $symbolRst = UpdaterTool::NYS;
                break;
            case 1:
                $symbolRst = UpdaterTool::FAIL;
                break;
            case 2:
                $symbolRst = UpdaterTool::PASS;
                break;
            case 3:
                $symbolRst = UpdaterTool::PASS_CHL;
                break;
            default:

        }
        return $symbolRst;
    }

    private static function getUsersOfCurriculum($curriculumId){
        return ChallengeUser::leftjoin('users as U','challenge_users.user_id','U.id')
            ->leftjoin('classes as CL','challenge_users.class_id','CL.id')
            ->leftjoin('grades as GR','CL.grade_id','GR.id')
            ->leftjoin('curriculums as CU','GR.id','CU.grade_id')
            ->leftjoin('schools as SC','CL.school_id','SC.id')
            ->selectRaw('U.*, CL.id as class_id, SC.id as school_id, SC.city_id as city_id')
            ->where('CU.id','=',$curriculumId)
            ->where('U.enabled','=',1)
            ->whereNull('U.deleted_at')
            ->whereNull('CL.deleted_at')
            ->whereNull('GR.deleted_at')
            ->whereNull('CU.deleted_at')
            ->whereNull('SC.deleted_at')
            ->orderBy('U.id', 'asc')
            ->get();
    }

    private static function getStatusOfWorkbookByUser($userId, $curriculumId){
        return ChallengeUserWorkbookStatus::leftjoin('workbooks as W', 'challenge_user_workbook_statuses.workbook_id','W.id')
            ->leftjoin('units as UN', 'W.unit_id','UN.id')
            ->leftjoin('challenge_users as CU', 'challenge_user_workbook_statuses.challenge_user_id','CU.id')
            ->leftjoin('users as U','CU.user_id','U.id')
            ->where('UN.curriculum_id', '=',$curriculumId)
            ->where('CU.user_id', '=',$userId)
            ->where('U.enabled','=',1)
            ->whereNull('W.deleted_at')
            ->whereNull('UN.deleted_at')
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->selectRaw('challenge_user_workbook_statuses.*,  CONCAT("u_",UN.number, "_w_", W.number) AS colName ')
            ->get();
    }

    private static function getStatusOfUnitByUser($userId, $curriculumId){
        return ChallengeUserUnitStatus::leftjoin('units as UN', 'challenge_user_unit_statuses.unit_id','UN.id')
            ->leftjoin('challenge_users as CU', 'challenge_user_unit_statuses.challenge_user_id','CU.id')
            ->leftjoin('users as U','CU.user_id','U.id')
            ->where('UN.curriculum_id', '=',$curriculumId)
            ->where('CU.user_id', '=',$userId)
            ->where('U.enabled','=',1)
            ->whereNull('UN.deleted_at')
            ->whereNull('CU.deleted_at')
            ->whereNull('U.deleted_at')
            ->selectRaw('challenge_user_unit_statuses.*')
            ->get();
    }
}

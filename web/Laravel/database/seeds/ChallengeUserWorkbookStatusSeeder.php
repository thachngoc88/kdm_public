<?php

use Illuminate\Database\Seeder;

class ChallengeUserWorkbookStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.

//        DB::statement('ALTER TABLE kdm.challenge_user_workbook_statuses AUTO_INCREMENT = 1;');
        $challengeUsers = App\ChallengeUser::all();
        $workbooks = App\Workbook::all();
        //
        foreach ($challengeUsers as $challengeUser){
            foreach($workbooks as $workbook){
                $challengeWorkbookStatus = \App\ChallengeUserWorkbookStatus::create([
                    "workbook_id" => $workbook->id,
                    "challenge_user_id" => $challengeUser->id,
                    "status" => 0
                ]);
                $challengeWorkbookStatus->save();
            }
        }
        //
    }
}

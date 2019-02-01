<?php

use Illuminate\Database\Seeder;

class DebugChallengeUserWorkbookStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $challengeUsers = App\ChallengeUser::all();
        $workbooks = App\Workbook::all();
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

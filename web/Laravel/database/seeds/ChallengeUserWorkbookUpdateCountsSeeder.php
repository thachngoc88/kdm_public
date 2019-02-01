<?php

use Illuminate\Database\Seeder;

class ChallengeUserWorkbookUpdateCountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.

//        DB::statement('ALTER TABLE challenge_user_workbook_update_counts AUTO_INCREMENT = 1;');

        $challengeUsers = App\ChallengeUser::all();
        $wbs = App\Workbook::all();

        foreach ($challengeUsers as $challengeUser){
            foreach($wbs as $wb){
                $challengeUserWorkbookUpdateCount = \App\ChallengeUserWorkbookUpdateCount::create([
                    "workbook_id" => $wb->id,
                    "challenge_user_id" => $challengeUser->id,
                    "count" => 0
                ]);
                $challengeUserWorkbookUpdateCount->save();
            }
        }
    }
}

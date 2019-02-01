<?php

use Illuminate\Database\Seeder;

class DummyChallengeUserWorkbookStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.
        //DB::statement('SET CONSTRAINTS ALL DEFERRED;');
//        \App\ChallengeUserWorkbookStatus::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

//        $faker = Faker\Factory::create("ja_JP");
        DB::statement('ALTER TABLE kdm.challenge_user_workbook_statuses AUTO_INCREMENT = 1;');
        $challengeUsers = App\ChallengeUser::all();
        $workbooks = App\Workbook::all();
        //
        foreach ($challengeUsers as $challengeUser){
//            $grade = $challengeUser->klass->grade;
//            $curriculums = App\Curriculum::where('grade_id',$grade->id)->get();
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

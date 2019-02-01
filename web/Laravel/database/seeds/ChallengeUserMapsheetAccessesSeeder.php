<?php

use App\ChallengeUserMapsheetAccess;
use Illuminate\Database\Seeder;

class ChallengeUserMapsheetAccessesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::statement('ALTER TABLE challenge_user_mapsheet_accesses AUTO_INCREMENT = 1;');
        $challengeUsers = App\ChallengeUser::all();
        $currs = App\Curriculum::all();
        //
        foreach ($challengeUsers as $challengeUser){
            foreach($currs as $curr){
                $challengeusermapacc = ChallengeUserMapsheetAccess::create([
                    "curriculum_id" => $curr->id,
                    "challenge_user_id" => $challengeUser->id,
                ]);
                $challengeusermapacc->save();
            }
        }
    }
}

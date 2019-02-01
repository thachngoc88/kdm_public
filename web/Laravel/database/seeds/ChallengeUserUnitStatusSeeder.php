<?php

use Illuminate\Database\Seeder;

class ChallengeUserUnitStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.

//        DB::statement('ALTER TABLE kdm.challenge_user_unit_statuses AUTO_INCREMENT = 1;');
        $challengeUsers = \App\ChallengeUser::all();
        $units = \App\Unit::all();

        foreach ($challengeUsers as $challengeUser) {
            foreach ($units as $unit) {
                $challengeUnit = \App\ChallengeUserUnitStatus::create([
                    "unit_id" => $unit->id,
                    "challenge_user_id" => $challengeUser->id,
                    "status" => 0
                ]);
                $challengeUnit->save();
            }
        }
    }
}

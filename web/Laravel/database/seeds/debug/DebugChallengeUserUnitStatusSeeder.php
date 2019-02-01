<?php

use Illuminate\Database\Seeder;

class DebugChallengeUserUnitStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = Faker\Factory::create("ja_JP");

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

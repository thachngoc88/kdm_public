<?php

use App\FirstRecord;
use Illuminate\Database\Seeder;

class DummyFirstRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::statement('SET CONSTRAINTS ALL DEFERRED;');
        \App\ExistingPdf::truncate();
        DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');
//        DB::statement('ALTER TABLE first_records AUTO_INCREMENT = 1;');
        $faker = Faker\Factory::create("ja_JP");

        $challengeUsers = \App\ChallengeUser::all();
        foreach ($challengeUsers as $challengeUser) {
            $questions = \App\Question::all();
            foreach ($questions as $q) {
                if($q->workbook->unit->curriculum->grade->number == $challengeUser->klass->grade->number) {
                    $record = FirstRecord::create([
                        "challenge_user_id" => $challengeUser->id,
                        "question_id" => $q->id,
                        "record" => $faker->numberBetween(1, 2),
                    ]);
                    $record->save();
                }
            }
        }
    }
}

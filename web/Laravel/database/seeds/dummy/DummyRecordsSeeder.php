<?php

use Illuminate\Database\Seeder;
use \App\Record;

class DummyRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('ALTER TABLE records AUTO_INCREMENT = 1;');
        $faker = Faker\Factory::create("ja_JP");

        $challengeUsers = \App\ChallengeUser::all();
        foreach ($challengeUsers as $challengeUser) {
            $questions = \App\Question::all();
            foreach ($questions as $q) {
                if($q->workbook->unit->curriculum->grade->number == $challengeUser->klass->grade->number) {
                    $record = Record::create([
                        "challenge_user_id" => $challengeUser->id,
                        "question_id" => $q->id,
                        // "record" => $faker->numberBetween(1, 2),
                    ]);
                    $record->save();
                }
            }
        }

    }
}

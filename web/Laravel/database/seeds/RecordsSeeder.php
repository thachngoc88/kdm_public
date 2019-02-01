<?php

use Illuminate\Database\Seeder;
use \App\Record;

class RecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.
//        Record::truncate();
        //DB::statement('ALTER SEQUENCE records_id_seq RESTART WITH 1;');
        DB::statement('ALTER TABLE records AUTO_INCREMENT = 1;');

        $challengeUsers = \App\ChallengeUser::all();
        $questions = \App\Question::all();

        foreach ($challengeUsers as $challengeUser) {
            foreach ($questions as $q) {
                if($q->workbook->unit->curriculum->grade->number === $challengeUser->klass->grade->number) {
                    $record = Record::create([
                        "challenge_user_id" => $challengeUser->id,
                        "question_id" => $q->id,
                        "record" => 0,
                    ]);
                    $record->save();
                }
            }
        }

    }
}

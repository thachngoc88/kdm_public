<?php

use Illuminate\Database\Seeder;
use \App\Answer;

class DummyAnswersSeeder extends Seeder
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
//        Answer::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $questions = \App\Question::all();

        foreach ($questions as $q) {
                $answer = Answer::create([
                    "question_id" => $q->id,
                    "text" => $faker->text($maxNbChars = 20),
                ]);
            $answer->save();
        }
    }
}

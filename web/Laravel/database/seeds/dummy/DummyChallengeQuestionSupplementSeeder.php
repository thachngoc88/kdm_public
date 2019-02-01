<?php

use Illuminate\Database\Seeder;

class DummyChallengeQuestionSupplementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //delete all data.
        //DB::statement('SET CONSTRAINTS ALL DEFERRED;');
//        \App\ChallengeQuestionSupplement::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

//        $faker = Faker\Factory::create("ja_JP");

        $challenges = \App\Challenge::all();

        foreach ($challenges as $challenge){
            $workbook = $challenge->workbook;
            $questions = \App\Question::where('workbook_id', $workbook->id)->get();
            $supplements = \App\Supplement
                ::join('workbooks as W', 'supplements.workbook_id', '=', 'W.id')
                ->where('W.unit_id', $workbook->unit->id)
                ->select('supplements.*')
                ->get();
            $supplementsLength = count($supplements);
            $i = 0;
            foreach($questions as $question){
                $index = $i < $supplementsLength ? $i : $supplementsLength - 1;
                $supplement = $supplements[$index];


                /*echo("challenge_id:  {$challenge->id}" . PHP_EOL);
                echo("question_id:   {$question->id}" . PHP_EOL);
                echo("supplement_id: {$supplement->id}" . PHP_EOL);*/
                $challengeQuestionSupplement = \App\ChallengeQuestionSupplement::create([
                    "challenge_id"  => $challenge->id,
                    "question_id"   => $question->id,
                    "supplement_id" => $supplement->id,
                ]);
                $challengeQuestionSupplement->save();
                $i++;
            }
        }




//        $units = \App\Unit::all();
//        foreach ($units as $unit){
//            $supplementsLength = count($unit->workbooks) - 1;
//
//            $challenge = \App\Challenge
//                ::join('workbooks as W', 'challenges.workbook_id', '=', 'W.id')
//                ->where('W.unit_id', $unit->id)
//                ->where('number', 0)
//                ->select('challenges.*')
//                ->first();
//
//            $workbookId = $challenge->workbook->id;
//
//
//            $challengeQuestions = \App\Question::where(
//                'workbook_id', $workbookId
//            )->get();
//
//            for ($i = 0; $i < $supplementsLength; $i++){
//                if(array_key_exists($i, $challengeQuestions)){
//                    $challengeQuestion = $challengeQuestions[$i];
//                }else {
//                    $challengeQuestion = $challengeQuestions[count($challengeQuestions) - 1];
//                }
//                $challengeQuestionsSupplement = \App\ChallengeQuestionSupplement
//                    ::where('challenge_id', $challenge->id)
//                    ->where('question_id', $challengeQuestion->id)
//                    ->first();
//
//                $supplement = Supplement::create([
//                    "workbook_id" => $workbookId,
//                    "challenge_questions_supplements_id" => $challengeQuestionsSupplement->id
//                ]);
//                $supplement->save();
//            }
//        }
    }
}

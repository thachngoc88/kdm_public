<?php

namespace Tests\Feature;

use App\Record;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MigrationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {

        $faker = \Faker\Factory::create("ja_JP");
        $challengeUsers = \App\ChallengeUser::all();
        foreach ($challengeUsers as $challengeUser) {
            $questions = \App\Question::all();
            foreach ($questions as $q) {
                if($q->workbook->unit->curriculum->grade->number == $challengeUser->klass->grade->number) {
                    $record = Record::create([
                        "challenge_user_id" => $challengeUser->id,
                        "question_id" => $q->id,
                        "record" => $faker->numberBetween(1, 2),
                    ]);
                    //                $record->save();
                }
            }
        }

        $this->assertTrue(true);
    }
}

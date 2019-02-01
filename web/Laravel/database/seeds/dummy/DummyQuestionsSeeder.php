<?php

use Illuminate\Database\Seeder;
use \App\Question;

class DummyQuestionsSeeder extends Seeder
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
//        Question::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');
        DB::statement('ALTER TABLE questions AUTO_INCREMENT = 1;');
        $faker = Faker\Factory::create("ja_JP");

        $workbooks = \App\Workbook::all();

        foreach ($workbooks as $wb) {
            for ($i = 0; $i < 10 ; $i++){
                $question = Question::create([
                    "workbook_id" => $wb->id,
                    "text" => $faker->text($maxNbChars = 20),
                ]);
                $question->save();
            }
        }
    }
}

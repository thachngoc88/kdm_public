<?php

use Illuminate\Database\Seeder;
use App\Subject;

class DummySubjectsSeeder extends Seeder
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
//        Subject::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');
        //
        $faker = Faker\Factory::create("ja_JP");

        for ($i = 0; $i < 2 ; $i++){
            $subject = Subject::create([
//                  "name" => $faker->unique()->name
                  "name" => ['国語','算数'][$i]
              ]);
            $subject->save();
        }
    }
}

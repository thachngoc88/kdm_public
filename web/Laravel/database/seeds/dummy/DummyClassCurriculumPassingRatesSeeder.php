<?php

use Illuminate\Database\Seeder;

class DummyClassCurriculumPassingRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //DB::statement('SET CONSTRAINTS ALL DEFERRED;');
//        \App\ClassCurriculumPassingRate::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $curriculums = \App\Curriculum::all();

        foreach ($curriculums as $cur) {
            for ($i = 0; $i < 10 ; $i++){
                $currpr =  \App\ClassCurriculumPassingRate::create([
                    "curriculum_id" => $cur->id,
                    "passing_rate" => 0.9,
                    "class_id" => 1,
                    "marking_log_id"=>1
                ]);
                $currpr->save();
            }
        }
    }
}

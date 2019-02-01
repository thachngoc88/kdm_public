<?php

use Illuminate\Database\Seeder;

class DummyPrefectureCurriculumPassingRateSeeder extends Seeder
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
//        \App\PrefectureCurriculumPassingRate::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $curriculums = \App\Curriculum::all();
        $prefectures =  \App\Prefecture::all();
        foreach ($curriculums as $curr) {
            foreach ($prefectures as $pre){
                $prepr =  \App\PrefectureCurriculumPassingRate::create([
                    "curriculum_id" => $curr->id,
                    "passing_rate" => 0.9,
                    "prefecture_id" => $pre->id,
                    "marking_log_id"=>1
                ]);
                $prepr->save();
            }
        }
    }
}

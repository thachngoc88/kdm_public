<?php

use Illuminate\Database\Seeder;

class DummyCityCurriculumPassingRatesSeeder extends Seeder
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
//        \App\CityCurriculumPassingRate::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $curriculums = \App\Curriculum::all();
        $cities =  \App\City::all();
        foreach ($curriculums as $curr) {
            foreach ($cities as $city){
                $citypr =  \App\CityCurriculumPassingRate::create([
                    "curriculum_id" => $curr->id,
                    "passing_rate" => 0.9,
                    "city_id" => $city->id,
                    "marking_log_id"=>1
                ]);
                $citypr->save();
            }
        }
    }
}

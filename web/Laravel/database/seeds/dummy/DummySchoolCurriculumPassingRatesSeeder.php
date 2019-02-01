<?php

use Illuminate\Database\Seeder;

class DummySchoolCurriculumPassingRatesSeeder extends Seeder
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
//        \App\SchoolCurriculumPassingRate::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $curriculums = \App\Curriculum::all();
        $schools = \App\School::all();
        foreach ($curriculums as $curr) {
            foreach ($schools as $school){
                $schoolpr =  \App\SchoolCurriculumPassingRate::create([
                    "curriculum_id" => $curr->id,
                    "passing_rate" => 0.9,
                    "school_id" => $school->id,
                    "marking_log_id"=>1
                ]);
                $schoolpr->save();
            }
        }
    }
}

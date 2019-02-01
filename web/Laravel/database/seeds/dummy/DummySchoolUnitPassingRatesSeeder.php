<?php

use Illuminate\Database\Seeder;

class DummySchoolUnitPassingRatesSeeder extends Seeder
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
//        \App\SchoolUnitPassingRate::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $units = \App\Unit::all();
        $schools = \App\School::all();
        foreach ($units as $un) {
            foreach ($schools as $school){
                $schoolpr =  \App\SchoolUnitPassingRate::create([
                    "unit_id" => $un->id,
                    "passing_rate" => 0.9,
                    "school_id" => $school->id,
                    "marking_log_id"=>1
                ]);
                $schoolpr->save();
            }
        }
    }
}

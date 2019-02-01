<?php

use Illuminate\Database\Seeder;

class DummySchoolPassingRatesSeeder extends Seeder
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
//        \App\SchoolPassingRate::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $workbooks = \App\Workbook::all();
        $schools = \App\School::all();
        foreach ($workbooks as $wb) {
            foreach ($schools as $school){
                $schoolpr =  \App\SchoolPassingRate::create([
                    "workbook_id" => $wb->id,
                    "passing_rate" => 0.1006,
                    "school_id" => $school->id,
                    "marking_log_id"=>1
                ]);
                $schoolpr->save();
            }
        }
    }
}

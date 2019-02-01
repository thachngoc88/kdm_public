<?php

use Illuminate\Database\Seeder;

class DummyPrefectureUnitPassingRateSeeder extends Seeder
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
//        \App\PrefectureUnitPassingRate::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $units = \App\Unit::all();
        $prefectures =  \App\Prefecture::all();
        foreach ($units as $un) {
            foreach ($prefectures as $pre){
                $prepr =  \App\PrefectureUnitPassingRate::create([
                    "unit_id" => $un->id,
                    "passing_rate" => 0.9,
                    "prefecture_id" => $pre->id,
                    "marking_log_id"=>1
                ]);
                $prepr->save();
            }
        }
    }
}

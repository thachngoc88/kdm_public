<?php

use Illuminate\Database\Seeder;

class DummyPrefecturePassingRateSeeder extends Seeder
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
//        \App\PrefecturePassingRate::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $workbooks = \App\Workbook::all();
        $prefectures =  \App\Prefecture::all();
        foreach ($workbooks as $wb) {
            foreach ($prefectures as $pre){
                $prepr =  \App\PrefecturePassingRate::create([
                    "workbook_id" => $wb->id,
                    "passing_rate" => 0.1006,
                    "prefecture_id" => $pre->id,
                    "marking_log_id"=>1
                ]);
                $prepr->save();
            }
        }
    }
}

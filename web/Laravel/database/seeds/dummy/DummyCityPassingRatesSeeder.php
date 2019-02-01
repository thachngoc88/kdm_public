<?php

use Illuminate\Database\Seeder;

class DummyCityPassingRatesSeeder extends Seeder
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
//        \App\CityPassingRate::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $workbooks = \App\Workbook::all();
        $cities =  \App\City::all();
        foreach ($workbooks as $wb) {
            foreach ($cities as $city){
                $citypr =  \App\CityPassingRate::create([
                    "workbook_id" => $wb->id,
                    "passing_rate" => 0.1006,
                    "city_id" => $city->id,
                    "marking_log_id"=>1
                ]);
                $citypr->save();
            }
        }
    }
}

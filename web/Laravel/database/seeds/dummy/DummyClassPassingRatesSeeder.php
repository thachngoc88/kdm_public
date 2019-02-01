<?php

use Illuminate\Database\Seeder;

class DummyClassPassingRatesSeeder extends Seeder
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
//        \App\ClassPassingRate::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $workbooks = \App\Workbook::all();

        foreach ($workbooks as $wb) {
            for ($i = 0; $i < 10 ; $i++){
                $classpr =  \App\ClassPassingRate::create([
                    "workbook_id" => $wb->id,
                    "passing_rate" => 0.1006,
                    "class_id" => 1,
                    "marking_log_id"=>1
                ]);
                $classpr->save();
            }
        }
    }
}

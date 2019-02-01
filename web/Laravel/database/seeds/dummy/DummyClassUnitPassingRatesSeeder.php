<?php

use Illuminate\Database\Seeder;

class DummyClassUnitPassingRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //
        //DB::statement('SET CONSTRAINTS ALL DEFERRED;');
//        \App\ClassUnitPassingRate::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $units = \App\Unit::all();

        foreach ($units as $un) {
            for ($i = 0; $i < 10 ; $i++){
                $classpr =  \App\ClassUnitPassingRate::create([
                    "unit_id" => $un->id,
                    "passing_rate" => 0.9,
                    "class_id" => 1,
                    "marking_log_id"=>1
                ]);
                $classpr->save();
            }
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use \App\Supplement;

class DummySupplementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.
        //DB::statement('SET CONSTRAINTS ALL DEFERRED;');
//        Supplement::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

//        $faker = Faker\Factory::create("ja_JP");

        $workbooks = \App\Workbook::all();

        foreach ($workbooks as $workbook){
            if($workbook->number > 0){
                $supplement = Supplement::create([
                    "workbook_id" => $workbook->id,
                ]);
                $supplement->save();
            }
        }

    }
}

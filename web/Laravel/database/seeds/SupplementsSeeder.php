<?php

use Illuminate\Database\Seeder;
use \App\Supplement;

class SupplementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.

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

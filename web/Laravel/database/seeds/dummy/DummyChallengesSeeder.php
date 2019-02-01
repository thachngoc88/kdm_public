<?php

use Illuminate\Database\Seeder;
use \App\Challenge;

class DummyChallengesSeeder extends Seeder
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
//        Challenge::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $wbs = \App\Workbook::all();

        foreach ($wbs as $wb){
            if($wb->number === 0){
                $challenge = Challenge::create([
                    "workbook_id" => $wb->id,
                ]);
                $challenge->save();
            }
        }
    }
}

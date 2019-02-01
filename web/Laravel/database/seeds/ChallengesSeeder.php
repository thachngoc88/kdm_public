<?php

use Illuminate\Database\Seeder;
use \App\Challenge;

class ChallengesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.

        $wbs = \App\Workbook::where('number', '=', 0)->get();

        foreach ($wbs as $wb){
            $challenge = Challenge::create([
                "workbook_id" => $wb->id,
            ]);
            $challenge->save();
        }
    }
}

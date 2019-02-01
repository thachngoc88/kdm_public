<?php

use Illuminate\Database\Seeder;

class DummyMakingLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i = 0; $i < 100 ; $i++){
            $markinglog = \App\MarkingLog::create();
            $markinglog->save();
        }
    }
}

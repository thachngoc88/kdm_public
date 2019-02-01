<?php

use Illuminate\Database\Seeder;
use App\Subject;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.

        for ($i = 0; $i <= 1 ; $i++){
            $subject = Subject::create([
                  "name" => ['国語','算数'][$i]
              ]);
            $subject->save();
        }
    }
}

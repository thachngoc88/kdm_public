<?php

use App\Curriculum;
use App\Grade;
use Illuminate\Database\Seeder;

class CurriculumsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.


        for ($g = 1; $g <= 1 ; $g++){
            for ($s = 1; $s <= 2 ; $s++) {
                $curriculum = Curriculum::create([
                    "name" => "",
                    "grade_id" => $g,
                    "subject_id" => $s
                ]);

                $curriculum->save();
            }
        }
    }
}

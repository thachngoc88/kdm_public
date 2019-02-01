<?php

use Illuminate\Database\Seeder;
use App\Grade;
use function Sodium\increment;

class GradesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.

        for ($i = 4; $i < 5 ; $i++){ // 5年だけ
            $grade = Grade::create([
                "number" => $i + 1
            ]);

            $grade->save();
        }
    }
}

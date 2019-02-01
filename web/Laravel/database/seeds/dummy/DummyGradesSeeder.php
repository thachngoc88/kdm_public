<?php

use Illuminate\Database\Seeder;
use App\Grade;
use function Sodium\increment;

class DummyGradesSeeder extends Seeder
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
//        Grade::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');
        //
        /*$faker = Faker\Factory::create("ja_JP");*/

        for ($i = 4; $i < 5 ; $i++){ // 5年だけ
            $grade = Grade::create([
                /*"number" => $faker->unique()->numberBetween(1, 6)*/
                "number" => $i + 1
            ]);

            $grade->save();
        }
    }
}

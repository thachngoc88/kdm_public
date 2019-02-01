<?php

use Illuminate\Database\Seeder;

class DummyMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //delete all data.
        //DB::statement('SET CONSTRAINTS ALL DEFERRED;');
//        \App\Message::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $faker = Faker\Factory::create("ja_JP");

        $conditions = \App\Condition::all();

        foreach ($conditions as $c) {
            for($i = 0; $i < 3; $i++){
                $message = \App\Message::create([
                    "condition_id" => $c->id,
                    "text" => $faker->text($maxNbChars = 20),
                    "order" => $i + 1,
                ]);
                $message->save();
            }
        }
    }
}

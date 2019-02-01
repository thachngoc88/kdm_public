<?php

use Illuminate\Database\Seeder;

class MessagesSeeder extends Seeder
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
        DB::statement('ALTER TABLE messages AUTO_INCREMENT = 1;');

        $conditions = \App\Condition::all();

        foreach ($conditions as $c) {
            for($i = 0; $i < 3; $i++){
                $message = \App\Message::create([
                    "condition_id" => $c->id,
                    "text" => "メッセージ" . strval($i + 1),
                    "order" => $i + 1,
                ]);
                $message->save();
            }
        }
    }
}

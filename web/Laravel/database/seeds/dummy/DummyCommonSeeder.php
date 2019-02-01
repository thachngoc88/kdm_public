<?php

use Illuminate\Database\Seeder;
class DummyCommonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }
    protected function randChars($valid_chars,$length){
        $random_string = "";
        $num_valid_chars = strlen($valid_chars);

        for ($i = 0; $i < $length; $i++)
        {
            $random_pick = mt_rand(1, $num_valid_chars);
            $random_char = $valid_chars[$random_pick-1];
            $random_string .= $random_char;
        }
        return $random_string;
    }
}

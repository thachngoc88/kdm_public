<?php

use Illuminate\Database\Seeder;

class ConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.

        $timings = \App\Timing::all();

        foreach ($timings as $t) {
            $tn = ($t->id - 1) % 3;
            switch($tn){
                case 0:
                    $d = [
                        ['5:00:00',  '11:00:00'],
                        ['11:00:00', '17:00:00'],
                        ['17:00:00', '5:00:00'],
                    ];
                    for($i = 0; $i < count($d); $i++){
                        $condition = \App\Condition::create([
                            "timing_id" => $t->id,
                            "condition" => "なし",
                            "time_from" => \Carbon\Carbon::parse($d[$i][0]),
                            "time_until" => \Carbon\Carbon::parse($d[$i][1]),
                            'order' => $i + 1,
                        ]);
                        $condition->save();
                    }
                    break;
                case 1:
                    $condition = \App\Condition::create([
                        "timing_id" => $t->id,
                        "condition" => "なし",
                        "time_from" => null,
                        "time_until" => null,
                        'order' => 4,
                    ]);
                    $condition->save();
                    break;
                case 2:
                    $d = [
                        'すべてのユニットをクリアした',
                        'ユニットをクリアした',
                        '項目をクリアした',
                        '結果を入力した',
                        '何も入力しない',
                    ];
                    for($i = 0; $i < count($d); $i++){
                        $condition = \App\Condition::create([
                            "timing_id" => $t->id,
                            "condition" => $d[$i],
                            "time_from" => null,
                            "time_until" => null,
                            'order' => $i + 5,
                        ]);
                        $condition->save();
                    }
                    break;
                default:
                    throw new Error();
            }
        }
    }
}

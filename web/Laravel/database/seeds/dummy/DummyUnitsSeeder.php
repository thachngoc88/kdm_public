<?php

use App\Unit;
use Illuminate\Database\Seeder;

class DummyUnitsSeeder extends Seeder
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
//        Unit::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');
        //
        $faker = Faker\Factory::create("ja_JP");

        $curriculms = \App\Curriculum::all();
        foreach ($curriculms as $curriculm) {
            for ($i = 0; $i < 10; $i++) {
                $unit = Unit::create([
                    "name" => $this->get($curriculm->grade->id, $curriculm->subject->id, $i),
                    "number" => $i + 1,
                    "curriculum_id" => $curriculm->id,
                ]);
                $unit->save();
            }
        }
    }

    private function get($gradeId, $subjectId, $index){
        $d =
            [
                [
                    [
                        "話し言葉と書き言葉〜お礼状〜",
                        "話し言葉と書き言葉〜吾輩は猫である〜",
                        "文や文章〜ガリレオの話〜",
                        "部首・漢和辞典〜たんぽぽのてんぷら〜",
                        "ローマ字〜折り紙〜",
                        "引用〜読書案内〜",
                        "敬語〜案内状〜",
                        "様子や気持ちを表すことば〜手ぶくろを買いに〜",
                        "故事成語・ことわざ・慣用句〜推敲〜",
                        "比喩・倒置〜杜子春〜〜風の又三郎〜",
                    ],
                    [
                        "4年生の復習",
                        "整数の性質",
                        "小数のかけ算",
                        "小数のわり算",
                        "分数のたし算ひき算",
                        "図形の面積",
                        "体積",
                        "単位量あたりの大きさ",
                        "図形の角",
                        "割合とグラフ",
                    ]
                ],
                [
                    [
                        "5年生の復習",
                        "整数の性質",
                        "小数のかけ算",
                        "小数のわり算",
                        "分数のたし算ひき算",
                        "図形の面積",
                        "体積",
                        "単位量あたりの大きさ",
                        "図形の角",
                        "割合とグラフ",
                    ],
                    [
                        "話し言葉と書き言葉〜お礼状〜",
                        "話し言葉と書き言葉〜吾輩は猫である〜",
                        "文や文章〜ガリレオの話〜",
                        "部首・漢和辞典〜たんぽぽのてんぷら〜",
                        "ローマ字〜折り紙〜",
                        "引用〜読書案内〜",
                        "敬語〜案内状〜",
                        "様子や気持ちを表すことば〜手ぶくろを買いに〜",
                        "故事成語・ことわざ・慣用句〜推敲〜",
                        "比喩・倒置〜杜子春〜〜風の又三郎〜",
                    ]
                ]
            ];
        return $d[$gradeId - 1][$subjectId - 1][$index];
    }
}

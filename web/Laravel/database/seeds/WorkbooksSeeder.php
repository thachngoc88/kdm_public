<?php

use Illuminate\Database\Seeder;
use \App\Workbook;

class WorkbooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.
        DB::statement('ALTER TABLE workbooks AUTO_INCREMENT = 1;');

        $units = \App\Unit::all();
        foreach ($units as $unit) {
            echo("unit {$unit}" . PHP_EOL);
            $wbTitles = $this->get($unit->curriculum->subject->id, $unit->number);
            $i = 0;
            foreach ($wbTitles as $title) {
                echo("title {$title}" . PHP_EOL);
                $workbook = Workbook::create([
                    "number" => $i,
                    "title" => $title,
                    "unit_id" => $unit->id,
                ]);
                $workbook->save();
                $i++;
            }
        }
    }

    private function get($subjectId, $unitNumber)
    {
        $d =
            [
                [
                    [
                        "チャレンジ",
                        "１年生の漢字(A)",
                        "１年生の漢字(B)",
                        "ひらがな・カタカナ(A)",
                        "ひらがな・カタカナ(B)",
                    ], [
                    "チャレンジ",
                    "１，２年生の漢字(A)",
                    "１，２年生の漢字(B)",
                    "基本のかなづかい(A)",
                    "基本のかなづかい(B)",
                ], [
                    "チャレンジ",
                    "２年生の漢字(A)",
                    "２年生の漢字(B)",
                    "主語・述語・修飾語・指示語・段落など(A)",
                    "主語・述語・修飾語・指示語・段落など(B)",
                ], [
                    "チャレンジ",
                    "２，３年生の漢字(A)",
                    "２，３年生の漢字(B)",
                    "漢和辞典の使い方",
                    "部首",
                ], [
                    "チャレンジ",
                    "３年生の漢字(A)",
                    "３年生の漢字(B)",
                    "ローマ字(A)",
                    "ローマ字(B)",
                ], [
                    "チャレンジ",
                    "３，４年生の漢字(A)",
                    "３，４年生の漢字(B)",
                    "「引用」について(A)",
                    "「引用」について(B)",
                ], [
                    "チャレンジ",
                    "４年生の漢字(A)",
                    "４年生の漢字(B)",
                    "「敬語」について(A)",
                    "「敬語」について(B)",
                ], [
                    "チャレンジ",
                    "４，５年生の漢字(A)",
                    "４，５年生の漢字(B)",
                    "様子や気持ちなどを表すことば(A)",
                    "様子や気持ちなどを表すことば(B)",
                ], [
                    "チャレンジ",
                    "５年生の漢字(A)",
                    "５年生の漢字(B)",
                    "故事成語・ことわざ・慣用句(A)",
                    "故事成語・ことわざ・慣用句(B)",
                ], [
                    "チャレンジ",
                    "５年生の漢字(A)",
                    "５年生の漢字(B)",
                    "表現の工夫(A)",
                    "表現の工夫(B)",
                ]
                ],
                [
                    [
                        "チャレンジ",
                        "分数のたし算・ひき算",
                        "小数のかけ算・わり算",
                        "整数の除法",
                        "平行四辺形，ひし形，台形",
                        "ともなって変わる2つの数量",
                    ], [
                    "チャレンジ",
                    "偶数・奇数",
                    "約数",
                    "倍数",
                ], [
                    "チャレンジ",
                    "整数×小数の計算",
                    "小数×小数の計算",
                    "計算のきまり",
                    "小数のかけ算を使って",
                ], [
                    "チャレンジ",
                    "整数÷小数の計算",
                    "小数÷小数の計算",
                    "あまりのあるわり算",
                    "どんな式になるかな",
                ], [
                    "チャレンジ",
                    "大きさの等しい分数",
                    "通分",
                    "分数のたし算",
                    "分数のひき算",
                    "分数と時間から",
                ], [
                    "チャレンジ",
                    "平行四辺形の面積の求め方",
                    "三角形の面積の求め方",
                    "ひし形の面積の求め方",
                    "台形の面積の求め方",
                    "三角形の高さと面積の関係",
                ], [
                    "チャレンジ",
                    "体積の単位",
                    "立方体・直方体の体積",
                    "いろいろな形の体積",
                    "容積",
                ], [
                    "チャレンジ",
                    "平均",
                    "単位量あたりの大きさ",
                ], [
                    "チャレンジ",
                    "三角形の角の大きさの和",
                    "四角形の角の大きさの和",
                    "多角形の角の大きさの和",
                    "円周率",
                ], [
                    "チャレンジ",
                    "割合",
                    "百分率と歩合",
                    "割合を使う問題",
                    "割合を表すグラフ",
                ],
                ]
            ];
        return $d[$subjectId - 1][$unitNumber - 1];
    }
}

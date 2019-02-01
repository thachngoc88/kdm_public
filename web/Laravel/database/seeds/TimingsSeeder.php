<?php

use Illuminate\Database\Seeder;
use App\Timing;
class TimingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.

        $curriculms = \App\Curriculum::all();

        foreach ($curriculms as $c) {
            for($i = 0; $i < 3; $i++){
                $timing = Timing::create([
                    "code" => $this->getCodes()[$i],
                    "curriculum_id" => $c->id,
                    "title" => $this->getTitles()[$i],
                    'order' => $i + 1
                ]);
                $timing->save();
            }
        }
    }

    private function getCodes(){
        return [
            "login",
            "download",
            "input",
        ];
    }

    private function getTitles(){
        return [
            "ログインして学びのマップシートを表示した",
            "ダウンロード画面から学びのマップシートに戻ってきた",
            "成績入力画面から学びのマップシートに戻ってきた",
        ];
    }
}

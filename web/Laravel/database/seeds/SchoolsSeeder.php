<?php

use App\School;
use App\User;
use Illuminate\Support\Facades\Hash;
class SchoolsSeeder extends CommonSeeder
{
    protected $schoolsHash = [
        1 => [
            ['末広小', 'すえひろしょう', 1, '', 'ga6802', 'J6xyMcsC'],
        ],
        2 => [
            ['三田小', 'みたしょう', 2, '', 'gb3802', 'P3tbUeZJ'],
        ],
        3 => [
            ['上星小', 'じょうせいしょう', 3, '', 'gc1068', 'Lf4Nsmre'],
        ],
        4 => [
            ['高峰小', 'たかみねしょう', 4, '', 'gd5741', 'uL8mvXzD'],
            ['中津第二小', 'なかつだいにしょう', 5, '', 'gd1583', 'St4y9r8q'],
        ],
        5 => [
            ['宮ヶ瀬小', 'みやがせしょう', 6, '', 'ge9821', 'W3r5gJYi'],
            ['緑小', 'みどりしょう', 7, '', 'ge1206', 'Fe2PfVzk'],
        ],
        6 => [
            ['大井小', 'おおいしょう', 8, '', 'gf9357', 'Pq4Uitp6'],
            ['相和小', 'そうわしょう', 9, '', 'gf1473', 'pJ5fN3BS'],
            ['上大井小', 'かみおおいしょう', 10, '', 'gf8057', 'Af6FbPXU'],
        ],
        7 => [
            ['川村小', 'かわむらしょう', 11, '', 'gg9075', 'M2sxD3e4'],
            ['三保小', 'みほしょう', 12, '', 'gg3201', 'H6p8uQAR'],
        ],
        8 => [
            ['富士見台小', 'ふじみだいしょう', 13, '', 'gh9367', 'xC7TmZWg'],
        ],
        9 => [
            ['茅ヶ崎小', 'ちがさきしょう', 14, '', 'gi9043', 'uN4WV8sg'],
            ['鶴が台小', 'つるがだいしょう', 15, '', 'gi4026', 'mT4QVHqM'],
            ['室田小', 'むろたしょう', 16, '', 'gi0679', 'g7Q2DwdW'],
        ],
        10 => [
            ['三崎小', 'みさきしょう', 17, '', 'gj9517', 'bB6CW7ew'],
            ['岬陽小', 'こうようしょう', 18, '', 'gj1675', 'C5tHgN6a'],
            ['名向小', 'なこうしょう', 19, '', 'gj4853', 'u2CJarFV'],
            ['南下浦小', 'みなみしたうらしょう', 20, '', 'gj1469', 'Zj6z8L3V'],
            ['上宮田小', 'かみみやだしょう', 21, '', 'gj0382', 'x3LCdgsS'],
            ['旭小', 'あさひしょう', 22, '', 'gj6014', 'Vr5hAjaP'],
            ['剣崎小', 'けんざきしょう', 23, '', 'gj0628', 'b8RAsQ2H'],
            ['初声小', 'はっせしょう', 24, '', 'gj1634', 'f9DeSAjv'],
        ],
        11 => [
            ['葉山小', 'はやましょう', 25, '', 'gk6410', 'K3eaA5Vm'],
            ['一色小', 'いっしきしょう', 26, '', 'gk7412', 'Ab9qjFce'],
            ['長柄小', 'ながえしょう', 27, '', 'gk4739', 'M9mX3vgL'],
            ['上山口小', 'かみやまぐちしょう', 28, '', 'gk0825', 'iZ7D8McU'],
        ],

    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.

//        DB::statement('ALTER TABLE schools AUTO_INCREMENT = 1;');
//        DB::statement('ALTER TABLE school_users AUTO_INCREMENT = 1;');

        $cities = \App\City::all();
        $firstUserId = 4;

        foreach ($cities as $city) {
            $cityId = $city->id;
            foreach ($this->schoolsHash[$cityId] as $s) {
                $school = School::create([
                    "name"    => $s[0],
                    "yomi"    => $s[1],
                    "number"  => $s[2],
                    "code"    => $s[3],
                    "order"   => $s[2],
                    "city_id" => $city->id
                ]);

                $school->save();


                \App\SchoolUser::create([
                    "user_id" => $firstUserId,
                    "school_id" => $school->id
                ])->save();
                $user = User::find($firstUserId);
                $user->login_id = $s[4];
                $user->password = Hash::make($s[5]);
                echo("id {$user->id}" . PHP_EOL);
                echo("login_id {$user->login_id}" . PHP_EOL);
                echo("password {$s[5]}" . PHP_EOL);
                $user->save();

                $firstUserId ++;
            }
        }
    }
}

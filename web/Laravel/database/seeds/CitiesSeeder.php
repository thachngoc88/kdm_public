<?php

use App\City;
use App\CityUser;
use App\User;
use Illuminate\Support\Facades\Hash;
class CitiesSeeder extends CommonSeeder
{
    protected $cityNames = [
        "秦野市",
        "厚木市",
        "海老名市",
        "愛川町",
        "清川村",
        "大井町",
        "山北町",
        "藤沢市",
        "茅ヶ崎市",
        "三浦市",
        "葉山町",
    ];

    protected $cityUsersHash = [
        1 =>['sa2845','dQ7jBzME'],
        2 =>['sb4632','Sr6L5hEj'],
        3 =>['sc6579','V2hpJWRM'],
        4 =>['sd6259','E9gpACtS'],
        5 =>['se2745','gF9PYZux'],
        6 =>['sf6423','Dg9nWSEL'],
        7 =>['sg2347','g7Z6tvYH'],
        8 =>['sh7936','Q6jGXLuV'],
        9 =>['si6487','f6HekVmM'],
        10 =>['sj7495','K9bPfyr2'],
        11 =>['sk2983','M9qsDdZG'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::statement('ALTER TABLE kdm.cities AUTO_INCREMENT = 1;');
       for ($i = 0; $i < count($this->cityNames) ; $i++){
           $number = $i + 1;
            $city = City::create([
                "name" => $this->cityNames[$i],
                "prefecture_id" => 1,
                "number" => $number,
                "order" => $number
            ]);
            $city->save();

            $user_id = $number + 1;
            CityUser::create([
                "user_id" => $user_id,
                "city_id" => $city->id
            ])->save();

            $user = User::find($user_id);
            $user->login_id = $this->createLoginId($user);
            $password = $this->createPass($user);
            $user->password = Hash::make($password);
            $user->save();

            echo("id:       {$user->id}" . PHP_EOL);
            echo("login_id: {$user->login_id}" . PHP_EOL);
            echo("password: {$password}" . PHP_EOL);
        }
    }
    private function createLoginId(User $user){
        return $this->cityUsersHash[$user->id - 1][0];
    }
    protected function createPass(User $user){
        return $this->cityUsersHash[$user->id - 1][1];
    }
}
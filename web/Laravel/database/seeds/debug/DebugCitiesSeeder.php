<?php

use App\City;
use App\CityUser;
use App\User;
use Illuminate\Support\Facades\Hash;
class DebugCitiesSeeder extends CommonSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create("ja_JP");
        DB::statement('ALTER SEQUENCE cities_id_seq RESTART WITH 1;');
        DB::statement('TRUNCATE TABLE cities CASCADE;');
        DB::statement('ALTER SEQUENCE city_users_id_seq RESTART WITH 1;');
       for ($i = 0; $i < 2 ; $i++){
            $city = City::create([
                "name" => $this->names()[$i],
                "prefecture_id" => 1,
                "order" => $i + 1
            ]);
            $city->save();
            $user_id = $i + 2;
            CityUser::create([
                "user_id" => $user_id,
                "city_id" => $city->id
            ])->save();

            $user = User::find($user_id);
            $user->login_id = $this->createLoginId($user);
            $password = $this->createPass($user);
            $user->password = Hash::make($password);
            echo("id {$user->id}" . PHP_EOL);
            echo("login_id {$user->login_id}" . PHP_EOL);
            echo("password {$password}" . PHP_EOL);
            if($user->enabled == 0)
                $user->enabled = 1;
            $user->save();
        }
    }
    private function names(){
        return [
            "A市",
            "B市"
        ];
    }
    private function createLoginId(User $user){
        //
        if(array_key_exists($user->id, $this->userCity()))
        {
            //echo("login_id have: {$this->userCity()[$user->id][0]}" . PHP_EOL);
            return $this->userCity()[$user->id][0];

        }
        //
        $codeCity = "sk";
        $valid_fourDigit = "23456789";
        do {
            $fourDigit = $this->randChars($valid_fourDigit, 4);
            $result = $codeCity . $fourDigit;
            $input = array('login_id' => $result);
            $validator = Validator::make(array_filter($input), [
                'login_id' => 'unique:users,login_id'
            ]);
        }
        while($validator->fails());
        return $result;
    }
    protected function createPass(User $user){
        if(array_key_exists($user->id, $this->userCity()))
        {
            return $this->userCity()[$user->id][1];

        }
        //
        $valid_Pass = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";
        $passUpdate = $this->randChars($valid_Pass, 8);
        return $passUpdate;
    }
    private function userCity(){
        return [
            2=>['sa7789','nfuNUTyC'],
            3=>['sb0981','Ds832akH'],

        ];

    }
}
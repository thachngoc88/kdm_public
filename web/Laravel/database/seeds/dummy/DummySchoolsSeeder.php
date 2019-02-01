<?php

use App\School;
use App\User;
use Illuminate\Support\Facades\Hash;
class DummySchoolsSeeder extends CommonSeeder
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
//        School::truncate();
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');
        //
        $faker = Faker\Factory::create("ja_JP");
        DB::statement('ALTER TABLE schools AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE school_users AUTO_INCREMENT = 1;');
        $cities = \App\City::all();
        $user_id = 13;
        foreach ($cities as $city) {
            $cityInx = $city->id - 1;
            if($cityInx == 0 || $cityInx == 1 || $cityInx == 2 || $cityInx == 7){
                for($i = 0; $i < 1; $i++) {
                    $this->createSchools_ScUser($cityInx, $i, $user_id, $city->id);
                    $user_id++;
                }
            }
            if($cityInx == 3 || $cityInx == 4 || $cityInx == 6){
                for($i = 0; $i < 2; $i++) {
                    $this->createSchools_ScUser($cityInx, $i, $user_id, $city->id);
                    $user_id++;
                }
            }
            if($cityInx == 5 || $cityInx == 8){
                for($i = 0; $i < 3; $i++) {
                    $this->createSchools_ScUser($cityInx, $i, $user_id, $city->id);
                    $user_id++;
                }
            }
            if($cityInx == 10){
                for($i = 0; $i < 4; $i++) {
                    $this->createSchools_ScUser($cityInx, $i, $user_id, $city->id);
                    $user_id++;
                }
            }
            if($cityInx == 9){
                for($i = 0; $i < 8; $i++) {
                    $this->createSchools_ScUser($cityInx, $i, $user_id, $city->id);
                    $user_id++;
                }
            }
            /*for ($i = 0; $i < 11; $i++) {
                $cityId = $i + 1;
                $n = $i % 1;
                $school = School::create([
                    "name" => $this->names()[$cityInx][$i],
                    "yomi" => $this->yomis()[$cityInx][$i],
                    "code" => $this->codes()[$cityInx][$i],
                    "city_id" => $city->id
                ]);

                $school->save();


                \App\SchoolUser::create([
                    "user_id" => $user_id,
                    "school_id" => $school->id
                ])->save();
                $user = User::find($user_id);
                $user->login_id = $this->createLoginId($user);
                $password = $this->createPass($user);
                $user->password = Hash::make($password);
                echo("id {$user->id}" . PHP_EOL);
                echo("login_id {$user->login_id}" . PHP_EOL);
                echo("password {$password}" . PHP_EOL);
                if ($user->enabled == 0)
                    $user->enabled = 1;
                $user->save();
                $user_id ++;
            }*/
        }

    }
    private function  createSchools_ScUser($cityInx, $i, $user_id, $city_id){
        echo('$cityInx '.$cityInx);
        echo('$i       '.$i);
        $school = School::create([
            "name" => $this->names()[$cityInx][$i],
            "yomi" => $this->yomis()[$cityInx][$i],
            "code" => $this->codes()[$cityInx][$i],
            "city_id" => $city_id
        ]);
        $school->save();


        \App\SchoolUser::create([
            "user_id" => $user_id,
            "school_id" => $school->id
        ])->save();
        $user = User::find($user_id);
        $user->login_id = $this->createLoginId($user);
        $password = $this->createPass($user);
        $user->password = Hash::make($password);
        echo("id {$user->id}" . PHP_EOL);
        echo("login_id {$user->login_id}" . PHP_EOL);
        echo("password {$password}" . PHP_EOL);
        if ($user->enabled == 0)
            $user->enabled = 1;
        $user->save();
    }
    private function names(){
        return [
            ["AA小"],
            ["BA小"],
            ["CA小"],
            ["DA小","DB小"],
            ["EA小","EB小",],
            ["FA小","FB小","FC小",],
            ["GA小","GB小"],
            ["HA小"],
            ["IA小","IB小","IC小",],
            ["JA小","JB小","JC小","JD小","JE小","JF小","JG小","JH小"],
            ["KA小","KB小","KC小","KD小"]
        ];
    }

    private function yomis(){
        return [
            ["AA yomis"],
            ["BA yomis"],
            ["CA yomis"],
            ["DA yomis","DB yomis"],
            ["EA yomis","EB yomis",],
            ["FA yomis","FB yomis","FC yomis",],
            ["GA yomis","GB yomis"],
            ["HA yomis"],
            ["IA yomis","IB yomis","IC yomis",],
            ["JA yomis","JB yomis","JC yomis","JD yomis","JE yomis","JF yomis","JG yomis","JH yomis"],
            ["KA yomis","KB yomis","KC yomis","KD yomis"]
        ];
    }

    private function codes(){
        return [
            ["AA code"],
            ["BA code"],
            ["CA code"],
            ["DA code","DB code"],
            ["EA code","EB code",],
            ["FA code","FB code","FC code",],
            ["GA code","GB code"],
            ["HA code"],
            ["IA code","IB code","IC code",],
            ["JA code","JB code","JC code","JD code","JE code","JF code","JG code","JH code"],
            ["KA code","KB code","KC code","KD code"]
        ];
    }
    private function createLoginId(User $user){
        //
        /*if(array_key_exists($user->id, $this->userSchool()))
        {
            //echo("login_id have: {$this->userSchool()[$user->id][0]}" . PHP_EOL);
            return $this->userSchool()[$user->id][0];

        }*/
        $codeCity = "ga";
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
    private function createPass(User $user){
        /*if(array_key_exists($user->id, $this->userSchool()))
        {
            //echo("password have: {$this->userSchool()[$user->id][1]}" . PHP_EOL);
            return $this->userSchool()[$user->id][1];

        }*/
        //
        $valid_Pass = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";
        //$passUpdate = $this->randChars($valid_Pass, 8);
        $passUpdate = "1111";
        return $passUpdate;
    }
    private function userSchool(){
        return [
            4=>['ga7358','rR39seN7'],
            5=>['gb1402','TH0AZr7n'],
            6=>['gc5835','C2Jd9bXt']


        ];

    }
}

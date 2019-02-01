<?php

use App\School;
use App\User;
use Illuminate\Support\Facades\Hash;
class DebugSchoolsSeeder extends CommonSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = 4;
       for ($i = 0; $i < 2 ; $i++){
            $cityId = $i + 1;
            $n = $i % 1;
            $school = School::create([
                "name" => $this->names()[$cityId - 1][$n],
                "yomi" => $this->yomis()[$cityId - 1][$n],
                "code" => $this->codes()[$cityId - 1][$n],
                "city_id" => $cityId
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
            if($user->enabled == 0)
                $user->enabled = 1;
            $user->save();
           $user_id++;
        }
    }

    private function names(){
        return [
            ["A小"],
            ["B小"],
        ];
    }

    private function yomis(){
        return [
            ["あ"],
            ["い"],
        ];
    }

    private function codes(){
        return [
            ["a"],
            ["b"],
        ];
    }
    private function createLoginId(User $user){
        //
        if(array_key_exists($user->id, $this->userSchool()))
        {
            //echo("login_id have: {$this->userSchool()[$user->id][0]}" . PHP_EOL);
            return $this->userSchool()[$user->id][0];

        }
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
        if(array_key_exists($user->id, $this->userSchool()))
        {
            //echo("password have: {$this->userSchool()[$user->id][1]}" . PHP_EOL);
            return $this->userSchool()[$user->id][1];

        }
        //
        $valid_Pass = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";
        $passUpdate = $this->randChars($valid_Pass, 8);
        return $passUpdate;
    }
    private function userSchool(){
        return [
            4=>['ga7410','p4JmPeQ8'],
            5=>['gb9124','eFUKtdjs']

        ];

    }
}

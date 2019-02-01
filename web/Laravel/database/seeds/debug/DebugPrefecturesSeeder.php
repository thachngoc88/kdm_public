<?php

use App\Prefecture;
use App\PrefectureUser;
use App\User;
use Illuminate\Support\Facades\Hash;
class DebugPrefecturesSeeder extends CommonSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       for ($i = 0; $i < 1 ; $i++){
            $prefecture = Prefecture::create([
//                "name" => $faker->unique()->name,
                "name" => ['神奈川県'][$i],
                "order" => $i + 1
            ]);

            $prefecture->save();
            $user_id = $i + 1;
            PrefectureUser::create([
                "user_id" => $user_id,
                "prefecture_id" => $prefecture->id
            ])->save();
            $user = User::find($user_id);
            $user->login_id = 'kk1103';//$this->createLoginId($user);
            $password = 'mwz2SF4Y';//$this->createPass($user);
            $user->password = Hash::make($password);
            echo("id {$user->id}" . PHP_EOL);
            echo("login_id {$user->login_id}" . PHP_EOL);
            echo("password {$password}" . PHP_EOL);
            if($user->enabled == 0)
            $user->enabled = 1;
            $user->save();
        }
    }

    private function createLoginId(User $user){
        //
        if(array_key_exists($user->id, $this->userPrefecture()))
        {
            //echo("login_id have: {$this->userPrefecture()[$user->id][0]}" . PHP_EOL);
            return $this->userPrefecture()[$user->id][0];

        }
        //
        $codeCity = "kk";
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
        if(array_key_exists($user->id, $this->userPrefecture()))
        {
            //echo("password have: {$this->userPrefecture()[$user->id][1]}" . PHP_EOL);
            return $this->userPrefecture()[$user->id][1];

        }

        $valid_Pass = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";
        $passUpdate = $this->randChars($valid_Pass, 8);
        return $passUpdate;
    }
    private function userPrefecture(){
        return [
            1=>['kk1103','mwz2SF4Y']
        ];

    }
}

<?php

use App\Prefecture;
use App\PrefectureUser;
use App\User;
use Illuminate\Support\Facades\Hash;
class PrefecturesSeeder extends CommonSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 0; $i < 1 ; $i++){
            $number = $i + 1;
            $prefecture = Prefecture::create([
                "name" => ['神奈川県'][$i],
                "number" => $number,
                "order" => $number
            ]);

            $prefecture->save();
            $user_id = $i + 1;
            PrefectureUser::create([
                "user_id" => $user_id,
                "prefecture_id" => $prefecture->id
            ])->save();
            $user = User::find($user_id);
            $user->login_id = 'kk2418';
            $password = '7CHmTSm5';
            $user->password = Hash::make($password);
            echo("id {$user->id}" . PHP_EOL);
            echo("login_id {$user->login_id}" . PHP_EOL);
            echo("password {$password}" . PHP_EOL);
            $user->save();
        }
    }
}

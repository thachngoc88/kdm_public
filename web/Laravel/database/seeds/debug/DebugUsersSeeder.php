<?php

use App\User;
use Illuminate\Database\Seeder;

class DebugUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('ALTER SEQUENCE users_id_seq RESTART WITH 1;');
        $faker = Faker\Factory::create('ja_JP');

        static $password;
        //insert
        for($i = 0; $i < 185; $i++)
        {
            $user = User::create([
                'login_id' => $faker->unique()->isbn10,
                'password' => $password ?: $password = bcrypt('secret'),
                'enabled' => 0,
            ]);

            $user->save();

        }
    }
}

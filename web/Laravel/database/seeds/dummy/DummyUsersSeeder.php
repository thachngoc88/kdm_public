<?php

use App\User;
use Illuminate\Database\Seeder;

class DummyUsersSeeder extends Seeder
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
         //User::truncate();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        //DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');
        //DB::statement('SET CONSTRAINTS ALL DEFERRED;');

        //faker
        $faker = Faker\Factory::create('ja_JP');

        static $password;
        //insert
        for($i = 0; $i < 2695; $i++)
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

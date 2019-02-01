<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.
        $faker = Faker\Factory::create('ja_JP');

        for($i = 0; $i < 2995; $i++)
        {
            $user = User::create([
                'login_id' => $faker->unique()->isbn10,
                'password' => "",
                'enabled' => 0,
                'order' => $i,
            ]);

            $user->save();

        }
    }
}

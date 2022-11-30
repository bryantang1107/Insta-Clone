<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        foreach (range(1, 10) as $value) {
            $name = $faker->name();
            $username =
                strtolower(
                    preg_replace(
                        '/[^A-Za-z0-9\-]/',
                        '',
                        str_replace(' ', '', $name)
                    )
                ) . $faker->unique->numberBetween(1, 1000);
            $email = $faker->email();
            $password = Hash::make(12345678);
            User::create([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => $password,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class createUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'fullname' => 'james kay 1',
            'email' => 'jameskay1@test.com',
            'password' => Hash::make('12345678')
        ]);

        User::create([
            'fullname' => 'james kay ',
            'email' => 'jameskay2@test.com',
            'password' => Hash::make('12345678')
        ]);

        User::create([
            'fullname' => 'james kay 3',
            'email' => 'jameskay3@test.com',
            'password' => Hash::make('12345678')
        ]);

        User::create([
            'fullname' => 'james kay 4',
            'email' => 'jameskay4@test.com',
            'password' => Hash::make('12345678')
        ]);

        User::create([
            'fullname' => 'james kay 5',
            'email' => 'jameskay5@test.com',
            'password' => Hash::make('12345678')
        ]);
    }
}

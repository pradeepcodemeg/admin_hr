<?php

use Illuminate\Database\Seeder;
use App\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'firstname' => 'Admin',
            'lastname' => 'Person',
            'role' => 'Admin',
            'image' => '/images/default_user_photo.png',
            'email' => 'admin@mailinator.com',
            'password' => Hash::make('Admin@123'),
            'status' => 'Active',
        ]);

    }
}

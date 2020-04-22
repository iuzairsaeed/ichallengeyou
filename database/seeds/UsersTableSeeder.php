<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [[
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@idu.com',
            'password' => Hash::make('secret')
        ], [
            'name' => 'Test User',
            'username' => 'user',
            'email' => 'user@idu.com',
            'password' => Hash::make('secret')
        ]
        ];
        User::insert($data);
    }
}

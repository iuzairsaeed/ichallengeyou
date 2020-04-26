<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Challenge;

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
            'role' => Admin(),
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@idu.com',
            'password' => Hash::make('secret')
        ], [
            'role' => Normal(),
            'name' => 'Test User',
            'username' => 'user',
            'email' => 'user@idu.com',
            'password' => Hash::make('secret')
        ]
        ];
        User::insert($data);
        // factory(User::class, 2)->create()->each(function($u) {
        //     $u->challenges()->saveMany(factory(Challenge::class, 5)->make());
        // });
    }
}

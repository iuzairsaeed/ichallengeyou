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
                'balance' => 0.00,
                'is_premium' => 1,
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@idu.com',
                'password' => Hash::make('secret'),
                'created_at' => now()
            ], [
                'role' => Normal(),
                'balance' => 1000.00,
                'is_premium' => 1,
                'name' => 'Test User 1',
                'username' => 'user1',
                'email' => 'user1@idu.com',
                'password' => Hash::make('secret'),
                'created_at' => now()
            ], [
                'role' => Normal(),
                'balance' => 0.00,
                'is_premium' => 0,
                'name' => 'Test User 2',
                'username' => 'user2',
                'email' => 'user2@idu.com',
                'password' => Hash::make('secret'),
                'created_at' => now()
            ], [
                'role' => Normal(),
                'balance' => 50.00,
                'is_premium' => 1,
                'name' => 'Test User 3',
                'username' => 'user3',
                'email' => 'user3@idu.com',
                'password' => Hash::make('secret'),
                'created_at' => now()
            ]
        ];
        User::insert($data);
        // factory(User::class, 2)->create()->each(function($u) {
        //     $u->challenges()->saveMany(factory(Challenge::class, 5)->make());
        // });
    }
}

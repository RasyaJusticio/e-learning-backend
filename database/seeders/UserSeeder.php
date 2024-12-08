<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private static $USERS;

    public function __construct()
    {
        $password = bcrypt('Password123');

        self::$USERS = [
            [
                'name' => 'Teacher',
                'username' => 'teacher',
                'email' => 'teacher@gmail.com',
                'password' => $password,
                'role' => 'teacher',
                'username_changed_at' => now()->subHours(24),
            ],
            [
                'name' => 'Student',
                'username' => 'student',
                'email' => 'student@gmail.com',
                'password' => $password,
                'role' => 'student',
                'username_changed_at' => now()->subHours(24),
            ]
        ];
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::$USERS as $user) {
            User::create($user);
        }
    }
}

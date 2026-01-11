<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin/Staff users
        User::create([
            'username' => 'admin',
            'email' => 'admin@orly.com',
            'password' => Hash::make('password'),
            'is_staff' => true,
        ]);

        User::create([
            'username' => 'staff',
            'email' => 'staff@orly.com',
            'password' => Hash::make('password'),
            'is_staff' => true,
        ]);

        // Regular customers
        User::create([
            'username' => 'john_doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'is_staff' => false,
        ]);

        User::create([
            'username' => 'jane_smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'is_staff' => false,
        ]);

        User::create([
            'username' => 'mike_wilson',
            'email' => 'mike@example.com',
            'password' => Hash::make('password'),
            'is_staff' => false,
        ]);

        User::create([
            'username' => 'sarah_jones',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            'is_staff' => false,
        ]);
    }
}

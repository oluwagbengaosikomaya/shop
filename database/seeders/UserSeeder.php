<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@shop.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // Regular User
        User::updateOrCreate(
            ['email' => 'customer@shop.com'],
            [
                'name' => 'Customer',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );
    }
}

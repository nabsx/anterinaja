<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin User
        User::create([
            'name' => 'Admin OjekOnline',
            'email' => 'admin@ojekonline.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'current_lat' => -6.2088,
            'current_lng' => 106.8456,
            'current_address' => 'Jl. Sudirman No.1, Jakarta Pusat',
        ]);

        // Sample Regular Users
        $users = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@user.test',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '081234567891',
                'current_lat' => -6.2146,
                'current_lng' => 106.8451,
                'current_address' => 'Jl. Thamrin No.10, Jakarta Pusat',
            ],
            [
                'name' => 'Ani Wijaya',
                'email' => 'ani@user.test',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '081234567892',
                'current_lat' => -6.2002,
                'current_lng' => 106.8165,
                'current_address' => 'Jl. Gatot Subroto No.5, Jakarta Selatan',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Generate 10 random users
        User::factory()->count(10)->create([
            'role' => 'user',
        ]);
    }
}
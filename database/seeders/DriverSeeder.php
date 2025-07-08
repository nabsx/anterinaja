<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    public function run()
    {
        // Sample Drivers
        $drivers = [
            [
                'name' => 'Agus Suparman',
                'email' => 'agus@driver.test',
                'password' => Hash::make('password123'),
                'role' => 'driver',
                'phone' => '081334567890',
                'current_lat' => -6.2088,
                'current_lng' => 106.8456,
                'current_address' => 'Jl. Sudirman No.1, Jakarta Pusat',
                'vehicle_type' => 'motor',
                'vehicle_number' => 'B 1234 ABC',
                'is_online' => true,
                'rating' => 4.8,
            ],
            [
                'name' => 'Dodi Kurniawan',
                'email' => 'dodi@driver.test',
                'password' => Hash::make('password123'),
                'role' => 'driver',
                'phone' => '081334567891',
                'current_lat' => -6.2146,
                'current_lng' => 106.8451,
                'current_address' => 'Jl. Thamrin No.10, Jakarta Pusat',
                'vehicle_type' => 'motor',
                'vehicle_number' => 'B 5678 DEF',
                'is_online' => true,
                'rating' => 4.5,
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'eko@driver.test',
                'password' => Hash::make('password123'),
                'role' => 'driver',
                'phone' => '081334567892',
                'current_lat' => -6.2002,
                'current_lng' => 106.8165,
                'current_address' => 'Jl. Gatot Subroto No.5, Jakarta Selatan',
                'vehicle_type' => 'mobil',
                'vehicle_number' => 'B 9012 GHI',
                'is_online' => false,
                'rating' => 4.2,
            ],
        ];

        foreach ($drivers as $driver) {
            User::create($driver);
        }

        // Generate 10 random drivers
        User::factory()->count(10)->create([
            'role' => 'driver',
            'vehicle_type' => fake()->randomElement(['motor', 'mobil']),
            'vehicle_number' => 'B ' . fake()->numberBetween(1000, 9999) . ' ' . fake()->lexify('???'),
            'is_online' => fake()->boolean(70), // 70% chance online
            'rating' => fake()->randomFloat(2, 3, 5),
        ]);
    }
}
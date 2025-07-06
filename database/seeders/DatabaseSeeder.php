<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@anterinaja.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0895422612739',
        ]);

        // Create sample user
        $user = User::create([
            'name' => 'Sulthan Yustr',
            'email' => 'Sulthan@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '087700313085',
        ]);

        // Create sample driver
        $driverUser = User::create([
            'name' => 'Berlian Kusumayudha',
            'email' => 'ian@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'phone' => '085972531910',
        ]);

        // Create driver profile
        Driver::create([
            'user_id' => $driverUser->id,
            'lat' => -7.2575,
            'lng' => 112.7521,
            'is_online' => true,
            'status' => 'available',
            'vehicle_type' => 'motor',
            'vehicle_plate' => 'B 1234 XYZ',
            'license_number' => 'SIM123456789',
        ]);

        // Create more sample drivers
        for ($i = 2; $i <= 5; $i++) {
            $driverUser = User::create([
                'name' => "Driver $i",
                'email' => "driver$i@ojek.com",
                'password' => Hash::make('password'),
                'role' => 'driver',
                'phone' => '08123456789' . $i,
            ]);

            Driver::create([
                'user_id' => $driverUser->id,
                'lat' => -7.2575 + (rand(-100, 100) / 10000),
                'lng' => 112.7521 + (rand(-100, 100) / 10000),
                'is_online' => rand(0, 1),
                'status' => 'available',
                'vehicle_type' => rand(0, 1) ? 'motor' : 'mobil',
                'vehicle_plate' => 'B ' . rand(1000, 9999) . ' XYZ',
                'license_number' => 'SIM' . rand(100000000, 999999999),
            ]);
        }
    }
}
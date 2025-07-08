<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            DriverSeeder::class,
        ]);
        
        // Jika ingin membuat data dummy order
        // $this->call(OrderSeeder::class);
    }
}
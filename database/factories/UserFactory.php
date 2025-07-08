<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        $jakartaLat = -6.2088;
        $jakartaLng = 106.8456;
        
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'remember_token' => Str::random(10),
            'phone' => '08' . fake()->numerify('##########'),
            'current_lat' => $jakartaLat + (fake()->randomFloat(4, -0.1, 0.1)),
            'current_lng' => $jakartaLng + (fake()->randomFloat(4, -0.1, 0.1)),
            'current_address' => fake()->address(),
            'avatar' => null,
        ];
    }

    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

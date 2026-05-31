<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'course' => fake()->randomElement(['OOP', 'Web Dev', 'Database', 'AI', 'Networks', 'Math']),
        ];
    }
}
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'serial_number' => fake()->unique()->regexify('[A-Z0-9]{3}[CDFGHJKLMNPQRSTVWXYZ][123456789CDFGHJKLMNPQRTVWXY][A-Z0-9]{3}P7QM'),
            'section' => fake()->randomElement(['client']),
            'user' => fake()->userName(),
            'text' => fake()->text(),
            'html' => fake()->randomHtml(),
            'timestamp' => time(),
        ];
    }
}

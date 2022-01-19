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
    public function definition()
    {
        return [
            'serial_number' => $this->faker->unique()->regexify('[A-Z0-9]{3}[CDFGHJKLMNPQRSTVWXYZ][123456789CDFGHJKLMNPQRTVWXY][A-Z0-9]{3}P7QM'),
            'section' => $this->faker->randomElement(['client']),
            'user' => $this->faker->userName,
            'text' => $this->faker->text,
            'html' => $this->faker->randomHtml,
            'timestamp' => time(),
        ];
    }
}

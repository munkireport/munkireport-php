<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserContactMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "channel" => "mail",
            "address" => $this->faker->email,
        ];
    }
}

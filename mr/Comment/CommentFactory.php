<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Comment\Comment::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'section' => $faker->randomElement(['client']),
        'user' => $faker->userName,
        'text' => $faker->text,
        'html' => $faker->text,
        'timestamp' => $faker->unixTime
    ];
});
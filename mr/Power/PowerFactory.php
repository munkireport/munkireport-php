<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Power\Power::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'manufacture_date' => $faker->dateTimeThisDecade, // YYYY-MM-DD
        'design_capacity' => $faker->numberBetween(5000, 6000),
        'max_capacity' => $faker->numberBetween(4000, 8000),
        'max_percent' => $faker->numberBetween(80, 100),
        'current_capacity' => $faker->numberBetween(0, 6000),
        'current_percent' => $faker->numberBetween(0, 100),
        'cycle_count' => $faker->numberBetween(0, 1000),
        'temperature' => $faker->numberBetween(2900, 3100),
        'condition' => $faker->randomElement(['Normal', 'Service Battery', 'No Battery']),
    ];
});
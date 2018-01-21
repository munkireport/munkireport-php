<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Power\Power::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'manufacture_date' => $faker->dateTimeThisDecade,
        'design_capacity' => $faker->numberBetween(0, 100),
        'max_capacity' => $faker->numberBetween(0, 100),
        'max_percent' => $faker->numberBetween(0, 100),
        'current_capacity' => $faker->numberBetween(0, 100),
        'current_percent' => $faker->numberBetween(0, 100),
        'cycle_count' => $faker->numberBetween(0, 5000),
        'temperature' => $faker->numberBetween(0, 50),
        'condition' => $faker->word
    ];
});
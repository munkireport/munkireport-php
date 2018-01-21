<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\TimeMachine\TimeMachine::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'last_success' => $faker->dateTimeThisMonth,
        'last_failure' => $faker->dateTimeThisMonth,
        'last_failure_msg' => $faker->text,
        'duration' => $faker->numberBetween(0, 100000)
    ];
});
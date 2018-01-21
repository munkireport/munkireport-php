<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Bluetooth\Bluetooth::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'battery_percent' => $faker->numberBetween(0, 100),
        'device_type' => $faker->randomElement(['mouse', 'keyboard'])
    ];
});
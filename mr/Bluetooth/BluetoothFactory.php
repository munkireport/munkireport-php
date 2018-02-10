<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Bluetooth\Bluetooth::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'battery_percent' => $faker->numberBetween(0, 100),
        'device_type' => $faker->randomElement([
            'bluetooth_power', 'apple magic mouse', 'apple wireless keyboard', 'magic trackpad 2', 'magic mouse 2',
            'magic keyboard'])
    ];
});
<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Event\Event::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'type' => $faker->randomElement(['info', 'success', 'warning', 'danger']),
        'module' => $faker->randomElement(['diskreport', 'reportdata']),
        'msg' => $faker->randomElement(['free_disk_space_less_than']),
        'data' => json_encode(Array('some' => 'data')),
    ];
});
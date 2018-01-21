<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Display\Display::class, function (Faker\Generator $faker) {
    return [
        'type' => $faker->numberBetween(0, 1),
        'display_serial' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'vendor' => $faker->word,
        'manufactured' => $faker->dateTimeThisDecade,
        'native' => $faker->randomNumber(3) . " x " . $faker->randomNumber(3),
        'model' => 'model'
    ];
});
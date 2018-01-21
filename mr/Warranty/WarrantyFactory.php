<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Warranty\Warranty::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'purchase_date' => $faker->dateTimeThisDecade,
        'end_date' => $faker->dateTimeThisDecade,
        'status' => $faker->word
    ];
});
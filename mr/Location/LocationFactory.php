<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Location\Location::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'address' => $faker->address,
        'altitude' => $faker->randomNumber(3),
        'currentstatus' => $faker->text,
        'ls_enabled' => $faker->boolean,
        'lastlocationrun' => $faker->dateTimeThisDecade,
        'lastrun' => $faker->dateTimeThisDecade,
        'latitude' => $faker->latitude,
        'latitudeaccuracy' => $faker->randomDigit,
        'longitude' => $faker->longitude,
        'longitudeaccuracy' => $faker->randomDigit,
        'stalelocation' => $faker->boolean
    ];
});
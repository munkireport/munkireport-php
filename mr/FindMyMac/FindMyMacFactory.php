<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\FindMyMac\FindMyMac::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'status' => $faker->word,
        'ownerdisplayname' => $faker->name,
        'email' => $faker->email,
        'personid' => $faker->randomDigit,
        'hostname' => $faker->userName
    ];
});
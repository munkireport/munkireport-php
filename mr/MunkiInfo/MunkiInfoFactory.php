<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\MunkiInfo\MunkiInfo::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'munkiinfo_key' => $faker->word,
        'munkiinfo_value' => $faker->text
    ];
});
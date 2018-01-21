<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\MunkiReport\MunkiReport::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'runtype' => $faker->text,
        'version' => $faker->numerify('#.#.#'),
        'errors' => $faker->randomDigit,
        'warnings' => $faker->randomDigit,
        'manifestname' => $faker->word,
        'error_json' => '{}',
        'warning_json' => '{}',
        'starttime' => $faker->dateTimeThisDecade,
        'endtime' => $faker->dateTimeThisDecade
    ];
});

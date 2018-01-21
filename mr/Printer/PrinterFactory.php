<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Printer\Printer::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'name' => $faker->word,
        'ppd' => '/Library/Printers/PPDs/' . $faker->word . '.ppd',
        'driver_version' => $faker->numerify('#.#'),
        'url' => $faker->url,
        'default_set' => $faker->words,
        'printer_status' => $faker->word,
        'printer_sharing' => $faker->word
    ];
});
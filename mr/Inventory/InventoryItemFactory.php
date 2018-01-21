<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Inventory\InventoryItem::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'name' => $faker->sentence,
        'version' => $faker->numerify('#.#.#'),
        'bundleid' => $faker->domainName,
        'bundlename' => $faker->sentence,
        'path' => '/path/to/app'
    ];
});
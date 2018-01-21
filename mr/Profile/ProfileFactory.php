<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Profile\Profile::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'profile_uuid' => $faker->uuid,
        'profile_name' => $faker->word,
        'profile_removal_allowed' => $faker->boolean,
        'payload_name' => $faker->word,
        'payload_display' => $faker->text,
        'payload_data' => ''
    ];
});
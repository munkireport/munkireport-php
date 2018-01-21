<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\ReportData\ReportData::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'console_user' => $faker->userName,
        'long_username' => $faker->name,
        'remote_ip' => $faker->ipv4,
        'uptime' => $faker->numberBetween(0, 100000),
        'reg_timestamp' => $faker->unixTime,
        'machine_group' => $faker->word
    ];
});
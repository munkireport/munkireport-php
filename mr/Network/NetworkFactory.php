<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Network\Network::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'service' => $faker->word,
        'order' => $faker->randomDigit,
        'status' => $faker->word,
        'ethernet' => $faker->macAddress,
        'clientid' => $faker->word,
        'ipv4conf' => $faker->word,
        'ipv4ip' => $faker->ipv4,
        'ipv4mask' => $faker->ipv4,
        'ipv4router' => $faker->ipv4,
        'ipv6conf' => $faker->word,
        'ipv6ip' => $faker->ipv6,
        'ipv6prefixlen' => $faker->randomDigit,
        'ipv6router' => $faker->ipv6
    ];
});
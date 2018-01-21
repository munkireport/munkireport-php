<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Certificate\Certificate::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'cert_exp_time' => $faker->dateTimeBetween('-10 days', '+1 month'),
        'cert_path' => '/path',
        'cert_cn' => 'CN=name,OU=name,O=name,DC=name,DC=com'
    ];
});
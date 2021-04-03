<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserContactMethod;
use Faker\Generator as Faker;

$factory->define(
    UserContactMethod::class, function (Faker $faker) {
    return [
        "channel" => "mail",
        "address" => $faker->email,
    ];
});

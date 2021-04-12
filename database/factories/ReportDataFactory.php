<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\ReportData::class, function (Faker\Generator $faker) {

    $machines = [
        ['JYVX', 'Macmini', 'Macmini8,1', 'Mac mini (2018)'],
        ['HDNK', 'MacBook', 'MacBook9,1', 'MacBook (Retina, 12-inch, Early 2016)'],
        ['MD6N', 'MacBook Pro', 'MacBookPro16,1', 'MacBook Pro (16-inch, 2019)'],
        ['H1DP', 'MacBook Pro', 'MacBookPro12,1', 'MacBook Pro (Retina, 13-inch, Early 2015)'],
        ['J9X6', 'iMac', 'iMac18,3', 'iMac (Retina 5K, 27-inch, 2017)'],
        ['JWDW', 'iMac', 'iMac19,2', 'iMac (Retina 4K, 21.5-inch, 2019)'],
        ['P7QM', 'MacPro', 'MacPro7,1', 'Mac Pro (2019)'],
    ];

    FakerDataStore::add('machine', $faker->randomElement($machines));
    list($modelcode, $machine_name, $machine_model, $machine_desc) = FakerDataStore::get('reportdata_factory', 'machine');

    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{3}[CDFGHJKLMNPQRSTVWXYZ][123456789CDFGHJKLMNPQRTVWXY][A-Z0-9]{3}') .$modelcode,
        'console_user' => $faker->userName,
        'long_username' => $faker->name,
        'remote_ip' => $faker->ipv4,
        'uptime' => $faker->numberBetween(0, 1000000),
        'reg_timestamp' => $faker->dateTimeBetween('-4 years')->format('U'),
        'machine_group' => 0,
        'archive_status' => $faker->numberBetween(0, 1),
        'timestamp' =>$faker->dateTimeBetween('-2 months')->format('U'),
    ];
});

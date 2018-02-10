<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Machine\Machine::class, function (Faker\Generator $faker) {
    $computerName = $faker->unique()->word;
    $machines = [
        ['iMac9,1', 'iMac (20-inch, Mid 2009)'],
        ['Macmini7,1', 'Mac mini (Late 2014)'],
        ['Macmini5,1', 'Mac mini (Mid 2011)'],
        ['MacBookPro12,1', 'MacBook Pro (Retina, 13-inch, Early 2015)'],
        ['MacBookPro8,1', 'MacBook Pro (13-inch, Late 2011)'],
        ['MacBookPro9,2', 'MacBook Pro (13-inch, Mid 2012)'],
        ['Macmini6,2', 'Mac mini (Late 2012)'],
        ['MacBook7,1', 'MacBook (13-inch, Mid 2010)'],
        ['iMac14,2', 'iMac (27-inch, Late 2013)'],
        ['iMac10,1', 'iMac (21.5-inch, Late 2009)'],
        ['iMac14,4', 'iMac (21.5-inch, Mid 2014)'],
        ['iMac12,1', 'iMac (21.5-inch, Mid 2011)'],
        ['iMac16,2', 'iMac (21.5-inch, Late 2015)'],
    ];

    list($machine_model, $machine_desc) = $faker->randomElement($machines);

    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'hostname' => $computerName . '.local',
        'machine_model' => $machine_model,
        'img_url' => '',
        'os_version' => '10.12.0',
        'buildversion' => '1',
        'machine_desc' => $machine_desc,
        'cpu' => $faker->text,
        'current_processor_speed' => $faker->randomFloat(2, 1, 4) . " GHz",
        'cpu_arch' => 'x86_64',
        'physical_memory' => $faker->randomElement([4,8,16,32]),
        'platform_uuid' => $faker->uuid,
        'number_processors' => $faker->randomElement([2,4,6,8]),
        'SMC_version_system' => $faker->randomFloat(2, 1, 3) . 'f' . $faker->randomDigit,
        'boot_rom_version' => $faker->regexify('[IMBP]{2}\.[0-9]{4}\.[A-Z]+'),
        'bus_speed' => $faker->randomDigit,
        'computer_name' => $computerName,
        'l2_cache' => $faker->randomDigit . ' MB',
        'machine_name' => 'iMac',
        'packages' => 1,
        'created_at' => $faker->dateTimeThisMonth
    ];
});
<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\Machine\Machine::class, function (Faker\Generator $faker) {
    $computerName = $faker->unique()->word;

    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'hostname' => $computerName . '.local',
        'machine_model' => $faker->randomElement([
            'MacBookPro6,1',
            'iMac15,1',
            'MacBookAir7,2',
            'MacPro5,1',
            'Macmini4,1',
            'MacBookPro8,2',
            'MacPro6,1'
        ]),
        'img_url' => '',
        'os_version' => '10.12.0',
        'buildversion' => '1',
        'machine_desc' => $faker->text,
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
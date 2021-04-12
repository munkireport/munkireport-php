<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\munkireport\models\Machine_model::class, function (Faker\Generator $faker) {

    $oses = [
        ['101206', '16G29'],
        ['101301', '17B48'],
        ['101503', '19D76'],
    ];

    // Allow standalone
    try {
        list($modelcode, $machine_name, $machine_model, $machine_desc) = FakerDataStore::get('machine_factory', 'machine');
    } catch (\Throwable $th) {
        list($modelcode, $machine_name, $machine_model, $machine_desc) = ['P7QM', 'MacPro', 'MacPro7,1', 'Mac Pro (2019)'];
    }
    
    list($os_version, $build) = $faker->randomElement($oses);

    $computerName = $faker->firstName() . '\'s ' . $machine_name;

    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{3}[CDFGHJKLMNPQRSTVWXYZ][123456789CDFGHJKLMNPQRTVWXY][A-Z0-9]{3}P7QM'),
        'hostname' => $faker->domainWord() . '.local',
        'machine_model' => $machine_model,
        'machine_desc' => $machine_desc,
        'img_url' => '',
        'cpu' => $faker->text,
        'current_processor_speed' => $faker->randomFloat(2, 1, 4) . " GHz",
        'cpu_arch' => 'x86_64',
        'os_version' => $os_version,
        'physical_memory' => $faker->randomElement([4,8,16,32]),
        'platform_uuid' => $faker->uuid,
        'number_processors' => $faker->randomElement([2,4,6,8]),
        'SMC_version_system' => $faker->randomFloat(2, 1, 3) . 'f' . $faker->randomDigit,
        'boot_rom_version' => $faker->regexify('[IMBP]{2}\.[0-9]{4}\.[A-Z]+'),
        'bus_speed' => $faker->randomElement([null, '1.07 Ghz']),
        'computer_name' => $computerName,
        'l2_cache' => $faker->randomElement([null, '3 MB', '6 MB']),
        'machine_name' => $machine_name,
        'packages' => 1,
        'buildversion' => $build,
    ];
});

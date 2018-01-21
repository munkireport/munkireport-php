<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\DiskReport\DiskReport::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'TotalSize' => $faker->randomNumber(7),
        'FreeSpace' => $faker->numberBetween(0, 20), // Low range to show warning/danger thresholds
        'Percentage' => $faker->numberBetween(0, 100),
        'SMARTStatus' => $faker->randomElement(['Not Supported', 'Verified', 'Failing']),
        'VolumeType' => $faker->randomElement(['ssd', 'hdd']),
        'BusProtocol' => $faker->randomElement(['PCI', 'SATA']),
        'Internal' => $faker->boolean,
        'MountPoint' => '/',
        'VolumeName' => 'Macintosh HD',
        'CoreStorageEncrypted' => $faker->boolean
    ];
});
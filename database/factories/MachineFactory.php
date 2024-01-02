<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MachineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $oses = [
            ['101206', '16G29'],
            ['101301', '17B48'],
            ['101503', '19D76'],
        ];

        // Allow standalone
//        try {
//            list($modelcode, $machine_name, $machine_model, $machine_desc) = FakerDataStore::get('machine_factory', 'machine');
//        } catch (\Throwable $th) {
            list($modelcode, $machine_name, $machine_model, $machine_desc) = ['P7QM', 'MacPro', 'MacPro7,1', 'Mac Pro (2019)'];
//        }

        list($os_version, $build) = $this->faker->randomElement($oses);

        $computerName = $this->faker->firstName() . '\'s ' . $machine_name;

        return [
            'serial_number' => fake()->unique()->regexify('[A-Z0-9]{3}[CDFGHJKLMNPQRSTVWXYZ][123456789CDFGHJKLMNPQRTVWXY][A-Z0-9]{3}P7QM'),
            'hostname' => fake()->domainWord() . '.local',
            'machine_model' => $machine_model,
            'machine_desc' => $machine_desc,
            'img_url' => '',
            'cpu' => fake()->text,
            'current_processor_speed' => fake()->randomFloat(2, 1, 4) . " GHz",
            'cpu_arch' => 'x86_64',
            'os_version' => $os_version,
            'physical_memory' => fake()->randomElement([4,8,16,32]),
            'platform_uuid' => fake()->uuid(),
            'number_processors' => fake()->randomElement([2,4,6,8]),
            'SMC_version_system' => fake()->randomFloat(2, 1, 3) . 'f' . $this->faker->randomDigit,
            'boot_rom_version' => fake()->regexify('[IMBP]{2}\.[0-9]{4}\.[A-Z]+'),
            'bus_speed' => fake()->randomElement([null, '1.07 Ghz']),
            'computer_name' => $computerName,
            'l2_cache' => fake()->randomElement([null, '3 MB', '6 MB']),
            'machine_name' => $machine_name,
            'packages' => 1,
            'buildversion' => $build,
        ];
    }
}

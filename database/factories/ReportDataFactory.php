<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReportDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $machines = [
            ['JYVX', 'Macmini', 'Macmini8,1', 'Mac mini (2018)'],
            ['HDNK', 'MacBook', 'MacBook9,1', 'MacBook (Retina, 12-inch, Early 2016)'],
            ['MD6N', 'MacBook Pro', 'MacBookPro16,1', 'MacBook Pro (16-inch, 2019)'],
            ['H1DP', 'MacBook Pro', 'MacBookPro12,1', 'MacBook Pro (Retina, 13-inch, Early 2015)'],
            ['J9X6', 'iMac', 'iMac18,3', 'iMac (Retina 5K, 27-inch, 2017)'],
            ['JWDW', 'iMac', 'iMac19,2', 'iMac (Retina 4K, 21.5-inch, 2019)'],
            ['P7QM', 'MacPro', 'MacPro7,1', 'Mac Pro (2019)'],
        ];

//        FakerDataStore::add('machine', $this->faker->randomElement($machines));
//        list($modelcode, $machine_name, $machine_model, $machine_desc) = FakerDataStore::get('reportdata_factory', 'machine');

        $machine = $this->faker->randomElement($machines);
        list($modelcode, $machine_name, $machine_model, $machine_desc) = $machine;
        return [
            'serial_number' => $this->faker->unique()->regexify('[A-Z0-9]{3}[CDFGHJKLMNPQRSTVWXYZ][123456789CDFGHJKLMNPQRTVWXY][A-Z0-9]{3}') .$modelcode,
            'console_user' => $this->faker->userName,
            'long_username' => $this->faker->name,
            'remote_ip' => $this->faker->ipv4,
            'uptime' => $this->faker->numberBetween(0, 1000000),
            'reg_timestamp' => $this->faker->dateTimeBetween('-4 years')->format('U'),
            'machine_group' => 0,
            'archive_status' => $this->faker->numberBetween(0, 1),
            'timestamp' =>$this->faker->dateTimeBetween('-2 months')->format('U'),
        ];
    }
}

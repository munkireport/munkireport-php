<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $messages = [
            ['info', 'reportdata', 'new_client', []],
            ['success', 'munkireport', 'munki.package_installed', ['count' => 3]],
            ['warning', 'diskreport', 'free_disk_space_less_than', ['gb' => 10]],
            ['danger', 'munkireport', 'munki.error', ['count' => 10]],
        ];

        list($type, $module, $msg, $data) = fake()->randomElement($messages);

        return [
            'type' => $type,
            'module' => $module,
            'msg' => $msg,
            'data' => json_encode($data),
            'timestamp' => fake()->dateTimeBetween('-1 month')->format('U'),
        ];
    }

}

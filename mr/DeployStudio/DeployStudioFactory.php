<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\DeployStudio\DeployStudio::class, function (Faker\Generator $faker) {
    $sn = $faker->unique()->regexify('[A-Z0-9]{12}');

    return [
        'serial_number' => $sn,
        'architecture' => 'x86_64',
        'cn' => 'CN=name,OU=orgunit,O=company,DC=company,DC=com',
        'dstudio_host_new_network_location' => $faker->word,
        'dstudio_host_primary_key' => $faker->randomElement(['sn', 'macaddress']),
        'dstudio_host_serial_number' => $sn,
        'dstudio_host_type' => $faker->word,
        'dstudio_hostname' => $faker->userName,
        'dstudio_last_workflow' => $faker->word,
        'dstudio_last_workflow_duration' => $faker->numberBetween(0, 5000),
        'dstudio_last_workflow_execution_date' => $faker->dateTimeThisDecade,
        'dstudio_last_workflow_status' => $faker->word,
        'dstudio_mac_addr' => $faker->macAddress,
        'dstudio_auto_disable' => $faker->word,
        'dstudio_auto_reset_workflow' => $faker->word,
        'dstudio_auto_started_workflow' => $faker->word,
        'dstudio_bootcamp_windows_computer_name' => $faker->userName,
        'dstudio_disabled' => $faker->boolean,
        'dstudio_group' => $faker->word,
        'dstudio_host_ard_field_1' => $faker->text,
        'dstudio_host_ard_field_2' => $faker->text,
        'dstudio_host_ard_field_3' => $faker->text,
        'dstudio_host_ard_field_4' => $faker->text,
        'dstudio_host_ard_ignore_empty_fields' => $faker->word,
        'dstudio_host_delete_other_locations' => $faker->word,
        'dstudio_host_model_identifier' => $faker->word
    ];
});
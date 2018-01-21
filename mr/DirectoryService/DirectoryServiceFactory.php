<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Mr\DirectoryService\DirectoryService::class, function (Faker\Generator $faker) {
    return [
        'serial_number' => $faker->unique()->regexify('[A-Z0-9]{12}'),
        'which_directory_service' => $faker->randomElement(['Local', 'Active Directory', 'LDAPv3']),
        'directory_service_comments' => $faker->text,
        'adforest' => $faker->domainName,
        'addomain' => $faker->word,
        'computeraccount' => $faker->word . '$',
        'createmobileaccount' => $faker->boolean,
        'requireconfirmation' => $faker->boolean,
        'forcehomeinstartup' => $faker->boolean,
        'mounthomeassharepoint' => $faker->boolean,
        'usewindowsuncpathforhome' => $faker->boolean,
        'networkprotocoltobeused' => $faker->randomElement(['smb', 'afp', 'nfs']),
        'defaultusershell' => '/bin/bash',
        'mappinguidtoattribute' => 'not set',
        'mappingusergidtoattribute' => 'not set',
        'mappinggroupgidtoattr' => 'not set',
        'generatekerberosauth' => $faker->boolean,
        'preferreddomaincontroller' => 'not set',
        'allowedadmingroups' => 'domain admins,enterprise admins',
        'authenticationfromanydomain' => $faker->boolean,
        'packetsigning' => 'allow',
        'packetencryption' => 'allow',
        'passwordchangeinterval' => $faker->numberBetween(1,28),
        'restrictdynamicdnsupdates' => 'not set',
        'namespacemode' => 'domain'
    ];
});
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessUnitSeeder extends Seeder
{
    /**
     * Set up a v5 business unit structure with two teams for testing BU roles
     */
    public function run(): void
    {
        $rows = [
            [
                'unitid' => 10,
                'property' => 'name',
                'value' => 'ACME Marketing'
            ],
            [
                'unitid' => 10,
                'property' => 'address',
                'value' => '1 ACME Way, Nowheretown, Nowheresville'
            ],
            [
                'unitid' => 10,
                'property' => 'link',
                'value' => 'https://acme.com/marketing'
            ],
            [
                'unitid' => 10,
                'property' => 'manager',
                'value' => '@ACMEMarketingManagers'
            ],
            [
                'unitid' => 10,
                'property' => 'archiver',
                'value' => '@ACMEMarketingArchivers'
            ],
            [
                'unitid' => 10,
                'property' => 'user',
                'value' => '@ACMEMarketingUsers'
            ],
            [
                'unitid' => 10,
                'property' => 'machine_group',
                'value' => '10'
            ],

            [
                'unitid' => 20,
                'property' => 'name',
                'value' => 'ACME Developers'
            ],
            [
                'unitid' => 20,
                'property' => 'address',
                'value' => '1 ACME Way, Nowheretown, Nowheresville'
            ],
            [
                'unitid' => 20,
                'property' => 'link',
                'value' => 'https://acme.com/development'
            ],
            [
                'unitid' => 20,
                'property' => 'manager',
                'value' => '@ACMEDevelopmentManagers'
            ],
            [
                'unitid' => 20,
                'property' => 'archiver',
                'value' => '@ACMEDevelopmentArchivers'
            ],
            [
                'unitid' => 20,
                'property' => 'user',
                'value' => '@ACMEDevelopmentUsers'
            ],
            [
                'unitid' => 20,
                'property' => 'machine_group',
                'value' => '20'
            ],
        ];

        DB::table('business_unit')->insert($rows);


        $machineGroups = [
            [
                'groupid' => 10,
                'property' => 'name',
                'value' => 'ACME Marketing Machine Group'
            ],
            [
                'groupid' => 10,
                'property' => 'key',
                'value' => '1448859D-1EA0-DD43-7C9C-605238328F3D'
            ],
            [
                'groupid' => 20,
                'property' => 'name',
                'value' => 'ACME Development Machine Group'
            ],
            [
                'groupid' => 20,
                'property' => 'key',
                'value' => '1448859D-1EA0-DD43-7C9C-605238328F3A'
            ],
        ];

        DB::table('machine_group')->insert($machineGroups);


        $users = [
            [
                'name' => 'acmemarketingmgr',
                'password' => bcrypt('acmemarketingmgr'), // It isn't secure, but you should not be running this seed anyway
                'email' => 'acme-marketing-manager@localhost',
                'display_name' => 'ACME Marketing Manager',
            ],
            [
                'name' => 'acmedevmgr',
                'password' => bcrypt('acmedevmgr'),
                'email' => 'acme-development-manager@localhost',
                'display_name' => 'ACME Development Manager',
            ],
        ];

        \App\User::insert($users);

        $userRoles = [
            [
                'unitid' => 10,
                'property' => 'manager',
                'value' => 'acmemarketingmgr'
            ],
            [
                'unitid' => 20,
                'property' => 'manager',
                'value' => 'acmedevmgr'
            ]
        ];

        DB::table('business_unit')->insert($userRoles);


    }
}

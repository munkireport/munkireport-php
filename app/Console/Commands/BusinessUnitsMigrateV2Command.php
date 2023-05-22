<?php

namespace App\Console\Commands;

use App\BusinessUnit;
use App\MachineGroup;
use Compatibility\BusinessUnit as CompatibleBusinessUnit;
use Compatibility\MachineGroup as CompatibleMachineGroup;
use Illuminate\Console\Command;

class BusinessUnitsMigrateV2Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'business-units:migrate-v2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Business Unit data from MunkiReport v5 to Business Units v2';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $machineGroupIds = CompatibleMachineGroup::select('groupid')->distinct()->get();
        foreach ($machineGroupIds as $machineGroupId) {
            $props = CompatibleMachineGroup::where('groupid', $machineGroupId->groupid)->get();
            $upgraded = new MachineGroup();

            foreach ($props as $prop) {
                switch ($prop->property) {
                    case 'name':
                        $upgraded->name = $prop->value;
                        break;
                    case 'key':
                        $upgraded->key = $prop->value;
                        break;
                    default:
                        $this->warn("Unhandled business unit property: {$prop->value} located in Business Unit ID {$prop->unitid}");
                }
            }

            if (!$upgraded->key) {
                $this->warn("Skipping machine group with no key");
                continue;
            }

            $upgraded->save();
            $this->info("UPGRADED MachineGroup: {$upgraded->name}");
        }


        $unitIds = CompatibleBusinessUnit::select('unitid')->distinct()->get();

        foreach ($unitIds as $unitId) {
            $props = CompatibleBusinessUnit::where('unitid', $unitId->unitid)->get();

            $upgraded = new BusinessUnit();
            $managers = [];
            $archivers = [];
            $users = [];
            $machineGroups = [];

            foreach ($props as $prop) {
                switch ($prop->property) {
                    case 'name':
                        $upgraded->name = $prop->value;
                        break;
                    case 'address':
                        $upgraded->address = $prop->value;
                        break;
                    case 'link':
                        $upgraded->link = $prop->value;
                        break;
                    case 'manager':
                        $managers[] = $prop->value;
                        break;
                    case 'archiver':
                        $archivers[] = $prop->value;
                        break;
                    case 'user':
                        $users[] = $prop->value;
                        break;
                    case 'machine_group':
                        $machineGroups[] = $prop->value;
                        break;
                    default:
                        $this->warn("Unhandled business unit property: {$prop->value} located in Business Unit ID {$prop->unitid}");
                }
            }

            $upgraded->save();
            $this->info("UPGRADED BusinessUnit: {$upgraded->name}");

        }

        $this->info("You can now start using business units v2 by specifying ALPHA_FEATURE_BUSINESS_UNITS_V2=true in your environment");
        return Command::SUCCESS;
    }
}

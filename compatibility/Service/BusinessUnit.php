<?php
namespace Compatibility\Service;

use Compatibility\BusinessUnit as BuModel;
use munkireport\models\Machine_group;
use Illuminate\Support\Str;

/**
 * BusinessUnit acts as a pseudo-repository for Business Units (v5).
 *
 * Because v5 Business Units are not row based models (they are more like lookup tables), this class is used
 * to assemble/disassemble them into the model updates.
 */
class BusinessUnit
{
    /**
     * Save a Business Unit (v5)
     *
     * The expected array might contain the following fields (decoded from form-encoded data):
     *
     * * name: business unit name
     * * groupid: associated machine group id (?)
     * * unitid: business unit id.
     * * address: street address.
     * * link: url
     * * machine_groups[]: array of machine group id as string
     * * users[], managers[], archivers[]: array of username or @group as string
     * * iteminfo[name, key]: array of machine groups to create or update
     *
     * A literal value of '#' where an array should be denotes an intentionally empty value (?)
     *
     * @param array $post_array Business unit attributes/properties
     * @return array The updated Business unit, or error message to display.
     */
    public function saveUnit(array $post_array): array
    {
        $out = [];

        if ( ! ($unitid = $post_array['unitid'] ?? false) ) {
            return [
                'error' => 'Unitid missing',
            ];
        }

        // Check if new unit
        if ($unitid == 'new') {
            $unitid = intval(BuModel::max('unitid')) + 1;
        }
        
        $out['unitid'] = $unitid;
        unset($post_array['unitid']);

        // Update machine groups
        if( isset($post_array['iteminfo'])){
            $this->_updateMachineGroups($post_array);
            unset($post_array['iteminfo']);    
        }

        $this->_updateUnitSettings($post_array, $out);
        $this->_updateUnitGroups($post_array, $out);

        return $out;
    }

    /**
     * Create/update machine groups for a business unit (v5).
     * Some other screens make use of /admin/save_machine_group to update detail.
     *
     * The $post_array parameter is taken by reference so that it can be modified in-place for output.
     *
     * @since v6 - also accepts a NULL key if creating a machine group
     * @param array &$post_array form data from /admin/save_business_unit
     * @return void
     */
    private function _updateMachineGroups(array &$post_array): void
    {
        $post_array['machine_groups'] = [];

        // If sent a '#', no items are in the iteminfo array
        if (in_array('#', $post_array['iteminfo'])) {
            return;
        }

        // Loop through iteminfo
        foreach ($post_array['iteminfo'] as $entry) {
            // No key, create new
            if ($entry['key'] === '' || is_null($entry['key'])) {
                $post_array['machine_groups'][] = $this->_createMachineGroup($entry);
            } else {
                // Add key to list
                $post_array['machine_groups'][] = intval($entry['key']);
            }
        }
    }

    /**
     * Create a new machine group (v5)
     *
     * For compatibility with v5 business units / machine groups
     *
     * @param array $entry Associative array containing keys for 'name' (machine group name) and 'key' which is discarded.
     * @return int The machine group ID of the created machine group.
     */
    private function _createMachineGroup(array $entry): int
    {
        $mg = new Machine_group;
        $newgroup = $mg->get_max_groupid() + 1;

        // Store name
        $mg->merge(array(
            'id' => '',
            'groupid' => $newgroup,
            'property' => 'name',
            'value' => $entry['name']));
        $mg->save();

        // Store GUID key
        $mg->merge(array(
            'id' => '',
            'groupid' => $newgroup,
            'property' => 'key',
            'value' => (string)Str::uuid()));
        $mg->save();

        return $newgroup;
    }

    private function _updateUnitSettings(&$post_array, &$out)
    {
        foreach ($post_array as $property => $val) {
            if (is_scalar($val)) {
                BuModel::updateOrCreate(
                    ['unitid' => $out['unitid'], 'property' => $property],
                    ['value' => $val]
                );
                $out[$property] = $val;
            }
        }
    }

    private function _updateUnitGroups(&$post_array, &$out)
    {
        // Translate groups to single entries
        $translate = [
            'keys' => 'key',
            'machine_groups' => 'machine_group',
            'users' => 'user',
            'managers' => 'manager',
            'archivers' => 'archiver',
        ];
        

        foreach ($post_array as $property => $val) {

            if (! is_array($val)) {
                continue;
            }

            // Check if this is a valid property
            if (! isset($translate[$property])) {
                $out['error'][] = 'Illegal property: '.$property;
                continue;
            }

            // Translate property to db entry
            $name =  $translate[$property];

            BuModel::where('unitid', $out['unitid'])
                ->where('property', $name)
                ->delete();

            foreach ($val as $entry) {
            // Empty array placeholder
                if ($entry === '#') {
                    $out[$property] =[];
                    continue;
                }
                $business_unit = new BuModel;
                $business_unit->unitid = $out['unitid'];
                $business_unit->property = $name;
                $business_unit->value = is_numeric($entry) ? 0 + $entry : $entry;
                $business_unit->save();
                $out[$property][] = is_numeric($entry) ? 0 + $entry : $entry;
            }
        }    
    }
}

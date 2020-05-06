<?php

namespace munkireport\lib;

use munkireport\models\Business_unit as BuModel;
use munkireport\models\Machine_group;


class BusinessUnit
{
    public function __construct() {
    }

    public function saveUnit($post_array)
    {
        $out = [];

        if ( ! ($unitid = $post_array['unitid'] ?? false) ) {
            jsonError('Unitid missing');
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

    private function _updateMachineGroups(&$post_array)
    {
        $post_array['machine_groups'] = [];

        // If sent a '#', no items are in the iteminfo array
        if (in_array('#', $post_array['iteminfo'])) {
            return;
        }

        // Loop through iteminfo
        foreach ($post_array['iteminfo'] as $entry) {
            // No key, create new
            if ($entry['key'] === '') {
                $post_array['machine_groups'][] = $this->_createMachineGroup($entry);
            } else {
                // Add key to list
                $post_array['machine_groups'][] = intval($entry['key']);
            }
        }
    }

    private function _createMachineGroup($entry)
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
            'value' => get_guid()));
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
        $translate = array(
            'keys' => 'key',
            'machine_groups' => 'machine_group',
            'users' => 'user',
            'managers' => 'manager');
        

        foreach ($post_array as $property => $val) {

            if (is_scalar($val)) {
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

<?php

namespace munkireport\controller;

use \Controller, \View, \Reportdata_model;
use munkireport\models\Business_unit;
use munkireport\models\Machine_group;

class admin extends Controller
{
    public function __construct()
    {
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

        if (! $this->authorized('global')) {
            die('You need to be admin');
        }
        
        // Connect to database
        $this->connectDB();

    }


    //===============================================================

    public function index()
    {
        echo 'Admin';
    }


    /**
     * Retrieve business units information
     *
     * @return void
     * @author
     **/
    public function get_business_units()
    {
        $business_unit = new Business_unit;
        $machine_group = new Machine_group;
        $out = array();
        foreach ($business_unit->select() as $unit) {
            $out[$unit->unitid][$unit->property] = $unit->value;
        }
    }

    //===============================================================

    /**
     * Save Machine Group
     *
     * @return void
     * @author
     **/
    public function save_machine_group()
    {
        if (isset($_POST['groupid'])) {
            $machine_group = new Machine_group;
            $groupid = $_POST['groupid'];

            // Empty groupid: create new
            if ($groupid === '') {
                $mg = new Machine_group;
                $groupid = $mg->get_max_groupid() + 1;
            }

            $out['groupid'] = intval($groupid);

            foreach ($_POST as $property => $val) {
            // Skip groupid
                if ($property == 'groupid') {
                    continue;
                }

                // Update business unit membership
                if ($property == 'business_unit') {
                    $bu = new Business_unit;
                    $bu->retrieveOne("property='machine_group' AND value=?", $_POST['groupid']);
                    $bu->unitid = $val;
                    $bu->property = 'machine_group';
                    $bu->value = $_POST['groupid'];
                    $bu->save();
                    $out['business_unit'] = intval($val);
                    continue;
                }

                if (is_scalar($val)) {
                    if ($val) {
                        $machine_group->id = '';
                        $machine_group->retrieveOne('groupid=? AND property=?', array($groupid, $property));
                        $machine_group->groupid = $groupid;
                        $machine_group->property = $property;
                        $machine_group->value = $val;
                        $machine_group->save();
                        $out[$property] = $val;
                    } else // Delete
                    {
                        $machine_group->deleteWhere('groupid=? AND property=?', array($groupid, $property));
                    }
                } else //array data
                {
                    $out['error'] = 'Unknown input: ' .$property;
                }
            }
            // Put key in array (for future purposes)
            if (isset($out['key'])) {
                $out['keys'][] = $out['key'];
                unset($out['key']);
            }
        } else {
            $out['error'] = 'Groupid is missing';
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    //===============================================================

    /**
     * Remove machine group
     *
     * @author
     **/
    public function remove_machine_group($groupid = '')
    {
        $out = array();

        if ($groupid !== '') {
            $mg = new Machine_group;
            if ($out['success'] = $mg->deleteWhere('groupid=?', $groupid)) {
            // Delete from business unit
                $bu = new Business_unit;
                $out['success'] = $bu->deleteWhere("property='machine_group' AND value=?", $groupid);
            }
            // Reset group in report_data
            Reportdata_model::where('machine_group', $groupid)
                ->update(['machine_group' => 0]);
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    //===============================================================

    /**
     * Save Business Unit
     *
     * @return void
     * @author
     **/
    public function save_business_unit()
    {
        $out = array();

        if (! $_POST) {
            $out['error'] = 'No data';
        } elseif (isset($_POST['unitid'])) {
            $business_unit = new Business_unit;

            // Translate groups to single entries
            $translate = array(
                'keys' => 'key',
                'machine_groups' => 'machine_group',
                'users' => 'user',
                'managers' => 'manager');

            $unitid = $_POST['unitid'];

            // Check if new unit
            if ($unitid == 'new') {
                $unitid = $business_unit->get_max_unitid() + 1;
            }

            $out['unitid'] = $unitid;

            // Check if there are changed items
            if (isset($_POST['iteminfo'])) {
                $groups = array();

                // If sent a '#', no items are in the iteminfo array
                // proceed with empty groups array
                if (! in_array('#', $_POST['iteminfo'])) {
                // Loop through iteminfo
                    foreach ($_POST['iteminfo'] as $entry) {
                    // No key, create new
                        if ($entry['key'] === '') {
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

                            $groups[] = $newgroup;
                        } else {
                            // Add key to list
                            $groups[] = intval($entry['key']);
                        }
                    }
                }

                // Set new machine_groups to list
                $_POST['machine_groups'] = $groups;
                unset($_POST['iteminfo']);
            }
            foreach ($_POST as $property => $val) {
            // Skip unitid
                if ($property == 'unitid') {
                    continue;
                }

                if (is_scalar($val)) {
                    $business_unit->id = '';
                    $business_unit->retrieveOne('unitid=? AND property=?', array($unitid, $property));
                    $business_unit->unitid = $unitid;
                    $business_unit->property = $property;
                    $business_unit->value = $val;
                    $business_unit->save();
                    $out[$property] = $val;
                } else //array data (machine_groups, users)
                {
                    // Check if this is a valid property
                    if (! isset($translate[$property])) {
                        $out['error'][] = 'Illegal property: '.$property;
                        continue;
                    }

                    // Translate property to db entry
                    $name =  $translate[$property];

                    $business_unit->deleteWhere('unitid=? AND property=?', array($unitid, $name));

                    foreach ($val as $entry) {
                    // Empty array placeholder
                        if ($entry === '#') {
                            $out[$property] = array();
                            continue;
                        }
                        $business_unit->id = '';
                        $business_unit->unitid = $unitid;
                        $business_unit->property = $name;
                        $business_unit->value = is_numeric($entry) ? 0 + $entry : $entry;
                        $business_unit->save();
                        $out[$property][] = is_numeric($entry) ? 0 + $entry : $entry;
                    }
                }
            }
        } else {
            $out['error'] = 'Unitid missing';
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    //===============================================================

    /**
     * remove_business_unit
     *
     * @return void
     * @author
     **/
    public function remove_business_unit($unitid = '')
    {
        $out = array();

        if ($unitid !== '') {
            $bu = new Business_unit;
            $out['success'] = $bu->deleteWhere('unitid=?', $unitid);
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    //===============================================================


    /**
     * Return BU data for unitid or all units if unitid is empty
     *
     * @return void
     * @author
     **/
    public function get_bu_data($unitid = "")
    {
        $obj = new View();
        $bu = new Business_unit;
        $obj->view('json', array('msg' => $bu->all($unitid)));
    }

    //===============================================================

    /**
     * Return Machinegroup data for groupid or all groups if groupid is empty
     *
     * @return void
     * @author
     **/
    public function get_mg_data($groupid = "")
    {
        $out = array();

        // Get created Machine Groups
        $mg = new Machine_group;
        foreach ($mg->all($groupid) as $arr) {
            $out[$arr['groupid']] = $arr;
        }

        // Get registered machine groups
        $result = Reportdata_model::selectRaw('machine_group, COUNT(*) AS cnt')
            ->groupBy('machine_group')
            ->get()
            ->toArray();
        foreach ($result as $obj) {
            if (! isset($out[$obj['machine_group']])) {
                $out[$obj['machine_group']] = [
                    'groupid' => $obj['machine_group'],
                    'name' => 'Group '.$obj['machine_group'],
                ];
            }
            $out[$obj['machine_group']]['cnt'] = $obj['cnt'];
        }

        $obj = new View();
        $obj->view('json', array('msg' => array_values($out)));
    }

    //===============================================================

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function show($which = '')
    {
        if ($which) {
            $data['page'] = 'clients';
            $data['scripts'] = array("clients/client_list.js");
            $view = 'admin/'.$which;
        } else {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
        }

        $obj = new View();
        $obj->view($view, $data);
    }
}

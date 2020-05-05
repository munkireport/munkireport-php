<?php

namespace munkireport\controller;

use \Controller, \View, \Reportdata_model;
use munkireport\models\Business_unit;
use munkireport\models\Machine_group;
use munkireport\lib\BusinessUnit;

class Admin extends Controller
{
    public function __construct()
    {
        // Check authorization
        $this->authorized() || jsonError('Authenticate first', 403);
        $this->authorized('global') || jsonError('You need to be admin', 403);
        
        // Connect to database
        $this->connectDB();

    }


    //===============================================================

    public function index()
    {
        echo 'Admin';
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

        jsonView($out);
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

        jsonView($out);
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
        $unit = new BusinessUnit();
        jsonView($unit->saveUnit($_POST));
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

        jsonView($out);
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
        $out = [];
        $units = Business_unit::get()
                    ->toArray();
        // jsonView($units);

        // // var_dump($units);
        // exit();

        foreach ($units as $obj) {
        // Initialize
            $obj = (object) $obj;
            if (! isset($out[$obj->unitid])) {
                $out[$obj->unitid] = array('users' => array(), 'managers' => array(), 'machine_groups' => array());
            }
            $out = ['users' => [], 'managers' => [], 'machine_groups' => []];
            switch ($obj->property) {
                case 'user':
                    $out[$obj->unitid]['users'][] = $obj->value;
                    break;
                case 'manager':
                    $out[$obj->unitid]['managers'][] = $obj->value;
                    break;
                case 'machine_group':
                    $out[$obj->unitid]['machine_groups'][] = intval($obj->value);
                    break;
                default:
                    $out[$obj->unitid][$obj->property] = $obj->value;
            }

            $out[$obj->unitid]['unitid'] = $obj->unitid;
        }

        // if ($unitid && $out) {
        //     return $out[$unitid];
        // } else {
        //     return array_values($out);
        // }
        jsonView(array_values($out));
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

        view('json', array('msg' => array_values($out)));
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

        view($view, $data);
    }
}

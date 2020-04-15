<?php

namespace munkireport\controller;

use \Controller, \View;
use munkireport\models\Business_unit;
use munkireport\models\Machine_group;

class Unit extends Controller
{
    public function __construct()
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data = array('session' => $_SESSION);

        echo 'BU dashboard<pre>';

        print_r($_SESSION);
        return;
    }

    /**
     * Get unit data for current user
     *
     * @author
     **/
    public function get_data()
    {
        $out = array();

        // Initiate session
        $this->authorized();

        if (isset($_SESSION['business_unit'])) {
        // Get data for this unit
            $unit = new Business_unit;
            $out = $unit->all($_SESSION['business_unit']);
        }

        jsonView($out);
    }

    /**
     * Get machine group data for current user
     *
     * @author
     **/
    public function get_machine_groups()
    {
        $out = array();

        if (isset($_SESSION['machine_groups'])) {
        // Get data for this unit
            $mg = new Machine_group;
            foreach ($_SESSION['machine_groups'] as $group) {
                if ($mg_data = $mg->all($group)) {
                    $out[] = $mg->all($group);
                } else if ($group != 0 && count($_SESSION['machine_groups']) != 0) // Not in Machine_group table
                {
                    $out[] = array(
                    'name' => 'Group '.$group,
                    'groupid' => $group);
                } else {
                    $out[] = array(
                    'name' => 'Unassigned',
                    'groupid' => $group);
                }
            }
        } else {
            $mg = new Machine_group;
            $out = $mg->all();
        }

        //Apply filter
        $groups = get_filtered_groups();
        foreach ($out as &$group) {
            $group['checked'] = in_array($group['groupid'], $groups);
        }

        usort($out, function($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });
        
        jsonView($out);
    }

    public function listing($which = '')
    {
        if ($which) {
            $data['page'] = 'clients';
            $data['scripts'] = array("clients/client_list.js");
            $view = 'listing/'.$which;
        } else {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
        }

        view($view, $data);
    }

    public function reports($which = 'default')
    {
        if ($which) {
            $data['page'] = 'clients';
            $view = 'report/'.$which;
        } else {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
        }

        view($view, $data);
    }
}

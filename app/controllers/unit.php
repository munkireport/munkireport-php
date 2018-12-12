<?php

namespace munkireport\controller;

use \Controller, \View;
use munkireport\models\Business_unit;
use munkireport\models\Machine_group;

class unit extends Controller
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

        $obj = new View();
        $obj->view('json', array('msg' => $out));
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
                } else // Not in Machine_group table
                {
                    $out[] = array(
                    'name' => 'Group '.$group,
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
        
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    /**
     * Add/remove a filter entry
     *
     * Currently only for machine_groups, but could contain
     * other filters (date, model, etc.)
     *
     * @author
     **/
    public function set_filter()
    {
        $out = array();

        $filter = $_POST['filter'];
        $action = $_POST['action'];
        $value = $_POST['value'];

        switch ($filter) {
            case 'machine_group':
                // Convert to int
                if (is_scalar($value)) {
                    $value = intval($_POST['value']);
                }
                break;

            default:
                $out['error'] = 'Unknown filter: '.$_POST['filter'];
                break;
        }


        if (! isset($out['error'])) {
        // Create filter if it does not exist
            if (! isset($_SESSION['filter'][$filter])) {
                $_SESSION['filter'][$filter] = array();
            }

            // Find value in filter
            $key = array_search($value, $_SESSION['filter'][$filter]);

            // If key in filter: remove
            if ($key !== false) {
                array_splice($_SESSION['filter'][$filter], $key, 1);
            }

            switch ($action) {
                case 'add': // add to filter
                    $_SESSION['filter'][$filter][] = $value;
                    break;
                case 'add_all': // add to filter
                    $_SESSION['filter'][$filter] = $value;
                    break;
                case 'clear': // clear filter
                    $_SESSION['filter'][$filter] = array();
                    break;
            }

            // Return current filter array
            $out[$filter] = $_SESSION['filter'][$filter];
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
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

        $obj = new View();
        $obj->view($view, $data);
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

        $obj = new View();
        $obj->view($view, $data);
    }
}

<?php

namespace munkireport\controller;

use \Controller, \View;

class Filter extends Controller
{
    private $registered_filters;

    public function __construct()
    {
        if (! $this->authorized()) {
            redirect('auth/login');
        }

        $this->registered_filters = [
            'machine_group' => [],
            'archived' => ['yes'],
            'archived_only' => [],
        ];
    }

        /**
     * Add/remove a filter entry
     *
     * Currently only for machine_groups, but could contain
     * other filters (date, model, etc.)
     *
     **/
    public function set_filter()
    {
        $out = [];

        $filter = $_POST['filter'] ?? '';
        $action = $_POST['action'] ?? '';
        $value = $_POST['value'] ?? '';

        switch ($filter) {
            case 'machine_group':
                // Convert to int
                if (is_scalar($value)) {
                    $value = intval($value);
                }
                break;
            case 'archived':
                break;
            case 'archived_only':
                break;
            default:
                jsonError('Unknown filter: '.$filter);
        }


        if (! isset($out['error'])) {
        // Create filter if it does not exist
            if (! isset($_SESSION['filter'][$filter])) {
                $_SESSION['filter'][$filter] = [];
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
                    $_SESSION['filter'][$filter] = [];
                    break;
            }

            // Return current filter array
            $out[$filter] = $_SESSION['filter'][$filter];
        }

        jsonView($out);
    }

    /**
     * Get filters
     *
     **/
    public function get_filter($filter = 'all')
    {
        if($filter == 'all'){
            jsonView($this->_render_filter());
        }
    }

    private function _render_filter()
    {
        return array_merge($this->registered_filters, $_SESSION['filter'] ?? []);
    }
}
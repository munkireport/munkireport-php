<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FilterController extends Controller
{
    private $registered_filters;

    public function __construct()
    {
        $this->middleware('auth');

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
     * @param Request $request
     */
    public function set_filter(Request $request)
    {
        $out = [];

        $filter = $request->post('filter', '');
        $action = $request->post('action', '');
        $value = $request->post('value', '');

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

        // Find value in session
        $sessionFilter = session()->get("filter.${filter}", []);
        $key = array_search($value, $sessionFilter);

        // If key in filter: remove
        if ($key !== false) {
            array_splice($sessionFilter, $key, 1);
        }

        switch ($action) {
            case 'add': // add to filter
                $sessionFilter[] = $value;
                break;
            case 'add_all': // add to filter
                $sessionFilter = $value;
                break;
            case 'clear': // clear filter
                $sessionFilter = [];
                break;
        }

        session()->put("filter.${filter}", $sessionFilter);

        // Return current filter array
        $out[$filter] = $sessionFilter;

        jsonView($out);
    }

    /**
     * Get filters
     *
     **/
    public function get_filter(Request $request, $filter = 'all')
    {
        if($filter == 'all'){
            jsonView($this->_render_filter($request));
        }
    }

    private function _render_filter(Request $request)
    {
        return array_merge($this->registered_filters, $request->session()->get('filter', []));
    }
}

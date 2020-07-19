<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use munkireport\lib\Tablequery;

class DatatablesController extends Controller
{
    public function __construct()
    {
        // Check authorization
//        $this->authorized() || jsonError('Authenticate first', 403);
        if (!Str::contains(config('auth.methods'), 'NOAUTH')) {
            $this->middleware('auth');
        }
    }

    public function data(Request $request)
    {
        // Sanitize the GET variables here.
        $cfg = array(
            'columns' => $request->input('columns', Array()),
            'order' => $request->input('order', Array()),
            'start' => $request->input('start', 0), // Start
            'length' => $request->input('length', -1), // Length
            'draw' => $request->input('draw', 0), // Identifier, just return
            'search' => $request->input('search.value', ''), // Search query
            'where' => $request->input('where', ''), // Optional where clause
            'mrColNotEmpty' => $request->input('mrColNotEmpty', ''), // Munkireport non empty column name
            'mrColNotEmptyBlank' => $request->input('mrColNotEmptyBlank', '') // Munkireport non empty column name
        );
        //echo '<pre>';print_r($_GET);return;

        $searchcols = array();

        // Add columns to config
        $cfg['search_cols'] = $searchcols;

        //echo '<pre>';print_r($cfg);

        try {
            // Get model
            $obj = new Tablequery($cfg);
            //echo '<pre>';print_r($obj->fetch($cfg));
            echo json_encode($obj->fetch($cfg));

            // Check for older php versions
            if (function_exists('json_last_error')) {
                // If there is an encoding error, show it
                if (json_last_error() != JSON_ERROR_NONE) {
                    echo json_last_error_msg();
                    print_r($obj->fetch($cfg));
                }
            }
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    'error' => $e->getMessage(),
                    'draw' => intval($cfg['draw'])
                )
            );
        }
    }
}

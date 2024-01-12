<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use munkireport\lib\Tablequery;

class DatatablesController extends Controller
{
    public function __construct()
    {
        // Check authorization
        $this->authorized() || jsonError('Authenticate first', 403);

        // Connect to database
        // $this->connectDB();
    }

    public function data(Request $request)
    {
        // Sanitize the GET variables here.
        $cfg = array(
            'columns' => array(),
            'order' => array(),
            'start' => 0, // Start
            'length' => -1, // Length
            'draw' => 0, // Identifier, just return
            'search' => '', // Search query
            'where' => '', // Optional where clause
            'mrColNotEmpty' => '', // Munkireport non empty column name
            'mrColNotEmptyBlank' => '' // Munkireport non empty column name
        );
        //echo '<pre>';print_r($_GET);return;

        $searchcols = array();

        $post = $request->only([
            'draw',
            'search',
            'columns',
            'start',
            'length',
            'order',
            'where',
            'mrColNotEmpty',
            'mrColNotEmptyBlank',
        ]);

        $search = $request->input('search');
        $cfg['search'] = $search['value'];

        $cfg['columns'] = $request->input('columns');
        $cfg['start'] = $request->input('start');


        // Process $_POST array
        foreach ($_POST as $k => $v) {
            if ($k == 'search') {
                $cfg['search'] = $v['value'];
            } elseif (isset($cfg[$k])) {
                $cfg[$k] = $v;
            }
        }// endforeach

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
        } catch (Exception $e) {
            echo json_encode(
                array(
                    'error' => $e->getMessage(),
                    'draw' => intval($cfg['draw'])
                )
            );
        }
    }
}

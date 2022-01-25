<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Compatibility\Service\Tablequery;

class DatatablesController extends Controller
{
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
            'search_cols' => [],
            'where' => $request->input('where', ''), // Optional where clause
            'mrColNotEmpty' => $request->input('mrColNotEmpty', ''), // Munkireport non empty column name
            'mrColNotEmptyBlank' => $request->input('mrColNotEmptyBlank', '') // Munkireport non empty column name
        );

        try {
            // Get model
            $obj = new Tablequery($cfg);
            return response()->json($obj->fetch($cfg));            
        } catch (\Exception $e) {
            return response()->json([
                    'error' => $e->getMessage(),
                    'draw' => intval($cfg['draw'])
            ]);
        }
    }
}

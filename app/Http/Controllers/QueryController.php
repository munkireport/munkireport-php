<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * QueryController serves the landing page for the dynamic, GraphQL based, query table.
 */
class QueryController extends Controller
{
    public function index()
    {
        return view('query.index');
    }
}

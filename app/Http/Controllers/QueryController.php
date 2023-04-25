<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * QueryController serves the landing page for the dynamic, GraphQL based, query table.
 */
class QueryController extends Controller
{
    public function index()
    {
        Inertia::setRootView('layouts.inertia');
        return Inertia::render('Query/Index');
    }
}

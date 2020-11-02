<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusinessUnitsController extends Controller
{
    public function index()
    {
        return view('business_units.index');
    }
}

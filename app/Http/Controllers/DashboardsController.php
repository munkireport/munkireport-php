<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * This controller provides a staging area for Dashboards V2, and should not be normally visible unless a feature
 * flag is enabled.
 *
 * @package App\Http\Controllers
 */
class DashboardsController extends Controller
{
    public function index() {
        return view('dashboards.index');
    }
}

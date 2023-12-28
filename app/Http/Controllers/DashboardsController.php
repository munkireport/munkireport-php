<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use munkireport\lib\Dashboard;

/**
 * This controller provides a staging area for Dashboards V2, and should not be normally visible unless a feature
 * flag is enabled.
 *
 * @package App\Http\Controllers
 */
class DashboardsController extends Controller
{
    /**
     * This controller action would have been for a Vue/SPA dashboards layout.
     * This is parked for a future release
     */
//    public function index() {
//        Inertia::setRootView('layouts.inertia');
//        return Inertia::render('Dashboards/Index', [
//            'dashboard_default_layout' => config('dashboard.default_layout')
//        ]);
//    }

    /**
     * This controller action returns an experimental blade component based dashboard.
     * Not sure if we will go forward with this option right now, but did not want to remove it entirely.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|null
     */
    public function index() {
        return view('dashboards.default', [
            'dashboard_layout' => config('dashboard.default_layout'),
        ]);
    }

    /**
     * Show the specified user-configured dashboard.
     *
     * Supersedes ShowController/dashboard/which method to use more idiomatic Laravel services.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|null|never
     */
    public function show(string $dashboard) {
        $dashboard = app(\munkireport\lib\Dashboard::class)->get($dashboard);
        if (is_null($dashboard)) {
            return abort(404);
        }

        // TODO: custom dashboards could originally specify their own view
        return view('dashboards.default', $dashboard);
    }

    /**
     * Show the default dashboard.
     *
     * This is currently a testbed for widget components and all other dashboards use the Dashboard class to render.
     */
    public function default() {
        return view('dashboards.default', [
            'dashboard_layout' => config('dashboard.default_layout'),
        ]);
    }
}

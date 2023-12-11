<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Show the currently authenticated user's profile, along with some actions that they may take which relate
     * to personal settings or other owned objects.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|void
     */
    public function index(Request $request) {
        return view('me.index', ['user' => $request->user()]);
    }

    /**
     * Display the user profile including personal settings.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|null
     */
    public function profile(Request $request) {
        return view('me.profile', [
            'locale' => $request->getLocale(),
            'current_theme' => $request->session()->get('theme', config('_munkireport.default_theme')),
        ]);
    }
}

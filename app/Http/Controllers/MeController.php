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
     * Display a list of API Tokens (From Laravel Sanctum) and allow the user to create or delete those tokens.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|void
     */
    public function tokens(Request $request) {
        return view('me.tokens');
    }
}

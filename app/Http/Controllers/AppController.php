<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class AppController extends Controller
{
//    public function index() {
//        return view('app');
//    }

    public function index()
    {
        Inertia::setRootView('layouts.inertia');
        return Inertia::render('Users/Index');
    }
}

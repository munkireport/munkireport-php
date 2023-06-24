<?php
namespace App\Http\Controllers;

use Inertia\Inertia;

class UsersController extends Controller
{
    public function index()
    {
        Inertia::setRootView('layouts.inertia');
        return Inertia::render('Users/Index');
    }
}

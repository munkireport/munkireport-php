<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserContactMethods;
use App\Packages;
use App\Http\Resources\Packages as PackagesResource;
use App\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class ModulesController extends Controller
{
    public function __construct(Packages $packages)
    {
        $this->packages = $packages;
    }

    public function index()
    {
        $modules = $this->packages->modules();
        return new PackagesResource(collect($modules));
    }
}

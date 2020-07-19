<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use munkireport\lib\Themes;

class SettingsController extends Controller
{
    public function __construct()
    {
        if (!Str::contains(config('auth.methods'), 'NOAUTH')) {
            $this->middleware('auth');
        }
    }

    //===============================================================

    /**
     * Set
     *
     * Set/Get theme value in $_SESSION
     * @param Request $request
     */
    public function theme(Request $request): JsonResponse
    {
        if ($request->has('set'))
        {
            // Check if valid theme
            $themeObj = new Themes();
            if(in_array($request->input('set'), $themeObj->get_list()))
            {
                $request->session()->put('theme', $request->input('set'));
            }
            else
            {
                return response()
                    ->json(sprintf('Error: theme %s unknown', $request->input('set')));
            }
        }

        return response()
            ->json($request->session()->get('theme', config('_munkireport.default_theme')));
    }
}

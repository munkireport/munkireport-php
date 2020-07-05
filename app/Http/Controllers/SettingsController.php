<?php

namespace App\Http\Controllers;

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
//        if (! $this->authorized()) {
//            mr_view('json', array('msg' => 'Not authorized'));
//
//            die();
//        }
    }

    //===============================================================

    /**
     * Set
     *
     * Set/Get theme value in $_SESSION
     *
     */
    public function theme()
    {
        if(isset($_POST['set']))
        {
            // Check if valid theme
            $themeObj = new Themes();
            if(in_array($_POST['set'], $themeObj->get_list()))
            {
                sess_set('theme', $_POST['set']);
            }
            else
            {
                mr_view('json', array('msg' => sprintf('Error: theme %s unknown', $_POST['set'])));
            }
        }

        mr_view('json', array('msg' => sess_get('theme', config('_munkireport.default_theme'))));
    }
}

<?php
class install extends Controller
{
    function __construct()
    {
    }

    function index()
    {
    	$data = array();
    	$obj = new View();
        $obj->view('install/install_script', $data);

    }
}
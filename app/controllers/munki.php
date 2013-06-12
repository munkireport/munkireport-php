<?php

class Munki extends Controller
{
	function __construct()
	{

	}


	function index()
	{
		$obj = new View();
		$obj->view('munki/index');
	}


	function manifests()
	{
		$obj = new View();
        $obj->view('munki/manifests');
	}


	function catalogs()
	{
		$obj = new View();
        $obj->view('munki/catalogs');
	}
}
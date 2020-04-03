<?php

namespace munkireport\controller;

use \Controller, \View;

/**
 * Module controller
 *
 * Loads the appropriate module, it looks a lot like the
 * engine class in kissmvc_core
 *
 * @package munkireport
 * @author AvB
 **/
class Module extends Controller
{
    public $module = 'default';
    public $action = 'index';
    private $moduleManager;

    public function __construct()
    {
        $this->moduleManager = getMrModuleObj();
    }

    public function index()
    {
    }


    public function load()
    {
        //Parse request (determine controller/action/params)
        $this->params = array();
        $p = func_get_args();
        if (isset($p[0]) && $p[0]) {
            $this->module=$p[0];
        }
        if (isset($p[1]) && $p[1]) {
            $this->action=$p[1];
        }
        if (isset($p[2])) {
            $this->params=array_slice($p, 2);
        }

        if (! preg_match('#^[A-Za-z0-9_-]+$#', $this->module)){
            $this->requestNotFound('illegal module name: '.$this->module);
        }

        //Route request to correct controller/action

        if (! $this->moduleManager->getmoduleControllerPath($this->module, $module_file)) {
            $this->requestNotFound('Module controller not found: '.$this->module);
        }

        //Create module obj
        require($module_file);
        $this->module_classname = '\\' . $this->module.'_controller';
        if (! class_exists($this->module_classname, false)) {
            $this->requestNotFound('Module class not found: '.$this->module_classname);
        }
        $this->module_obj = new $this->module_classname;

        //call controller function
        if (! preg_match('#^[A-Za-z_][A-Za-z0-9_-]*$#', $this->action) or ! method_exists($this->module_obj, $this->action)) {
            $this->requestNotFound('Invalid method name: '.$this->action);
        }

        // These methods don't require authentication
        $unProtectedActions = ["get_script", "index"];
        // Require authentication for all methods
        if( ! in_array($this->action, $unProtectedActions) && ! $this->module_obj->authorized())
        {
            $this->requestForbidden('Module controller filter');
        }
        
        // Connect to database
        $this->module_obj->connectDBWhenAuthorized();

        call_user_func_array(array( $this->module_obj, $this->action ), $this->params);
    }

    //Override this function for your own custom 404 page
    public function requestNotFound($msg = '')
    {
        header("HTTP/1.0 404 Not Found");
        die('<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>'.$msg.'<p>The requested URL was not found on this server.</p><p>Please go <a href="javascript: history.back( 1 )">back</a> and try again.</p><hr /><p>Powered By: <a href="http://kissmvc.com">KISSMVC</a></p></body></html>');
    }

    public function requestForbidden($msg = '')
    {
        header("HTTP/1.0 403 Forbidden");
        die('<html><head><title>403  Forbidden</title></head><body><h1>Forbidden</h1><p>'.$msg.'<p>The requested URL was allowed on this server.</p><p>Please authenticate first and try again.</p><hr /></body></html>');
    }

}

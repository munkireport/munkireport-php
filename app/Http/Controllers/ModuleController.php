<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use munkireport\lib\Modules;

class ModuleController extends Controller
{
    public $module = 'default';
    public $action = 'index';

    /**
     * @var Modules
     */
    private $moduleManager;

    /**
     * @var string The class name from a module to invoke
     */
    private $module_classname;

    /**
     * @var mixed The instance of the module_classname to invoke.
     */
    private $module_obj;

    /**
     * @var array|false|string[] Parameters passed to the module action
     */
    private $params;

    public function __construct()
    {
        $this->moduleManager = app(Modules::class);
    }

    public function index()
    {
    }

    public function invoke($module, $action, $params = "")
    {
        $this->module = $module;
        $this->action = $action;
        $this->params = explode("/", $params);

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

    /**
     * Render a 404 response and then exit immediately.
     *
     * @deprecated Throw a NotFoundException and let laravel render your error.
     * @param string $msg An error message to display in the body
     */
    public function requestNotFound($msg = '')
    {
        header("HTTP/1.0 404 Not Found");
        die('<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>'.$msg.'<p>The requested URL was not found on this server.</p><p>Please go <a href="javascript: history.back( 1 )">back</a> and try again.</p><hr /><p>Powered By: <a href="http://kissmvc.com">KISSMVC</a></p></body></html>');
    }

    /**
     * Render a 404 response and then exit immediately.
     *
     * @deprecated abort(403) and let laravel render the error.
     * @param string $msg An error message to display in the body
     */
    public function requestForbidden($msg = '')
    {
        header("HTTP/1.0 403 Forbidden");
        die('<html><head><title>403  Forbidden</title></head><body><h1>Forbidden</h1><p>'.$msg.'<p>The requested URL was allowed on this server.</p><p>Please authenticate first and try again.</p><hr /></body></html>');
    }
}

<?php
namespace MR\Kiss\Core;

//===============================================================
// Engine
// Parses HTTP request and routes to appropriate controller+function
//===============================================================
abstract class Engine
{
    protected $request_uri_parts=array();
    protected $controller;
    protected $controller_obj;
    protected $action;
    protected $params=array();
    protected $uri_string='';

    public function __construct($routes, $default_controller, $default_action, $uri_protocol = 'AUTO')
    {
        $this->controller = $default_controller;
        $this->controller=$default_controller;
        $this->action=$default_action;

        $this->fetchUriString($uri_protocol);

        //Process routes
        $req_uri = ltrim($this->uri_string, '/');
        foreach ($routes as $pat => $route) {
            if (preg_match('#^'.$pat.'$#', $req_uri)) {
                $req_uri = preg_replace('#^'.$pat.'$#', $route, $req_uri);
                break;
            }
        }

        //Explode URI parts
        $this->request_uri_parts = $req_uri ? explode('/', $req_uri) : array();

        //Parse request (determine controller/action/params)
        $this->params = array();
        $p = $this->request_uri_parts;
        if (isset($p[0]) && $p[0]) {
            $this->controller=$p[0];
        }
        if (isset($p[1]) && $p[1]) {
            $this->action=$p[1];
        }
        if (isset($p[2])) {
            $this->params=array_slice($p, 2);
        }

        //Route request to correct controller/action
        $controllerClass = 'munkireport\\controller\\' . ucfirst($this->controller);
        if ( ! class_exists($controllerClass, true)) {
            $this->requestNotFound('Controller class not found: '.$controllerClass);
        }
        $this->controller_obj = new $controllerClass;

        //call controller function
        if (! $this->validateAction() or ! method_exists($this->controller_obj, $this->action)) {
            $this->requestNotFound('Invalid function name: '.$this->action);
        }
        call_user_func_array(array( $this->controller_obj, $this->action ), $this->params);

        return $this;

    }

    // Check if action contains valid chars
    protected function validateAction()
    {
        return preg_match('#^[A-Za-z_][A-Za-z0-9_-]*$#', $this->action);
    }

    // The following three functions are blatantly copied from CodeIgniter (http://codeigniter.com)
    protected function fetchUriString($uri_protocol)
    {
        if (strtoupper($uri_protocol) === 'AUTO') {
            // Let's try the REQUEST_URI first, this will work in most situations
            if ($uri = $this->detectUri()) {
                $this->setUriString($uri);
                return;
            }

            // Is there a PATH_INFO variable?
            // Note: some servers seem to have trouble with getenv() so we'll test it two ways
            $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
            if (trim($path, '/') != '' && $path !== '/index.php') {
                $this->setUriString($path);
                return;
            }

            // No PATH_INFO?... What about QUERY_STRING?
            $path = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
            if (trim($path, '/') != '') {
                $this->setUriString($path);
                return;
            }

            // As a last ditch effort lets try using the $_GET array
            if (is_array($_GET) && count($_GET) === 1 && trim(key($_GET), '/') != '') {
                $this->setUriString(key($_GET));
                return;
            }

            // We've exhausted all our options...
            $this->uri_string = '';
            return;
        }

        $uri = strtoupper($uri_protocol);

        if ($uri === 'REQUEST_URI') {
            $this->setUriString($this->detectUri());
            return;
        }

        $path = (isset($_SERVER[$uri])) ? $_SERVER[$uri] : @getenv($uri);
        $this->setUriString($path);
    }

    // --------------------------------------------------------------------

    /**
     * Set the URI String
     *
     * @param   string
     * @return  void
     */
    public function setUriString($str)
    {
        // Filter out control characters
        //$str = remove_invisible_characters($str, FALSE);

        // If the URI contains only a slash we'll kill it
        $this->uri_string = ($str === '/') ? '' : $str;
    }

    // --------------------------------------------------------------------

    /**
     * Detects the URI
     *
     * This function will detect the URI automatically and fix the query string
     * if necessary.
     *
     * @return  string
     */
    protected function detectUri()
    {
        if (! isset($_SERVER['REQUEST_URI']) or ! isset($_SERVER['SCRIPT_NAME'])) {
            return '';
        }

        // Remove multiple slashes
        $uri = preg_replace('#/+#', '/', $_SERVER['REQUEST_URI']);
        $script = preg_replace('#/+#', '/', $_SERVER['SCRIPT_NAME']);

        if (strpos($uri, $script) === 0) {
            $uri = substr($uri, strlen($script));
        } elseif (strpos($uri, dirname($script)) === 0) {
            $uri = substr($uri, strlen(dirname($script)));
        }

        // This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
        // URI is found, and also fixes the QUERY_STRING server var and $_GET array.
        if (strncmp($uri, '?/', 2) === 0) {
            $uri = substr($uri, 2);
        }

        // Spit on ?
        $parts = preg_split('#\?#i', $uri, 2);
        $uri = $parts[0];

        // Check if we still have an ampersand in the uri
        // jquery will remove the second ? in an ajax call
        if (strpos($uri, '&') !== false) {
            $parts = preg_split('#&#i', $uri, 2);
            $uri = $parts[0];
        }

        if (isset($parts[1])) {
            $_SERVER['QUERY_STRING'] = $parts[1];
            parse_str($_SERVER['QUERY_STRING'], $_GET);
        } else {
            $_SERVER['QUERY_STRING'] = '';
            $_GET = array();
        }

        if ($uri == '/' or empty($uri)) {
            return '/';
        }

        $uri = parse_url($uri, PHP_URL_PATH);

        // Do some final cleaning of the URI and return it
        return str_replace(array('//', '../'), '/', trim($uri, '/'));
    }


    //Override this function for your own custom 404 page
    public function requestNotFound($msg = '')
    {
        header("HTTP/1.0 404 Not Found");
        die('<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>'.$msg.'<p>The requested URL was not found on this server.</p><p>Please go <a href="javascript: history.back( 1 )">back</a> and try again.</p><hr /><p>Powered By: <a href="http://kissmvc.com">KISSMVC</a></p></body></html>');
    }
}

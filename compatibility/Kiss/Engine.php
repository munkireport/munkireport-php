<?php
namespace Compatibility\Kiss;
use Compatibility\Kiss\Core\Engine as KISS_Engine;

//===============================================================
// Engine
//===============================================================
class Engine extends KISS_Engine
{
    public function __construct(&$routes, $default_controller, $default_action, $uri_protocol = 'AUTO')
    {
        $GLOBALS[ 'engine' ] = $this;

        parent::__construct($routes, $default_controller, $default_action, $uri_protocol);

    }

    public function requestNotFound($msg = '', $status_code = 404)
    {
        $data = array('status_code' => $status_code, 'msg' => '');

        // Don't show a detailed message when not in debug mode
        conf('debug') && $data['msg'] = $msg;


        mr_view('error/client_error', $data);

        exit;
    }

    public function get_uri_string()
    {
        return $this->uri_string;
    }
}

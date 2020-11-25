<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use munkireport\models\Machine_group, munkireport\lib\Modules, munkireport\lib\Dashboard;
use MR\Kiss\View;

// Munkireport version (last number is number of commits)
$GLOBALS['version'] = '6.0.0.4388';

// Return version without commit count
function get_version()
{
    return preg_replace('/(.*)\.\d+$/', '$1', $GLOBALS['version']);
}

//===============================================
// Alerts
//===============================================s

$GLOBALS['alerts'] = array();

/**
 * Add Alert
 *
 * @param string alert message
 * @param string type (danger, warning, success, info)
 **/
function alert($msg, $type = "info")
{
    $GLOBALS['alerts'][$type][] = $msg;
    request()->session()->flash($type, $msg);
}

/**
 * Add error message
 *
 * @param string message
 **/
function error($msg, $i18n = '')
{
    if ($i18n) {
        $msg = sprintf('<span data-i18n="%s">%s</span>', $i18n, $msg);
    }

    alert($msg, 'danger');
}

//===============================================
// Database
//===============================================

/**
 * For Laravel 7, this function no longer handles the PDOException by displaying an error message but passes it back
 * up into the App/Exception/Handler for reporting.
 *
 * @return PDO
 * @throws \PDOException
 */
function getdbh()
{
    return DB::connection()->getPdo();
}

function has_sqlite_db($connection)
{
  return find_driver($connection, 'sqlite');
}

function has_mysql_db($connection)
{
  return find_driver($connection, 'mysql');
}

function find_driver($connection, $driver)
{
  if( isset($connection['driver']) && $connection['driver'] == $driver){
    return true;
  }
  return false;
}

function add_mysql_opts(&$conn){
  $conn['options'] = [
    \PDO::MYSQL_ATTR_INIT_COMMAND => sprintf('SET NAMES %s COLLATE %s', $conn['charset'], $conn['collation'])
  ];
  if($conn['ssl_enabled']){
    foreach(['key', 'cert', 'ca', 'capath', 'cipher'] as $ssl_opt){
      if($conn['ssl_'. $ssl_opt]){
        $conn['options'][constant('PDO::MYSQL_ATTR_SSL_'.strtoupper($ssl_opt))] = $conn['ssl_'. $ssl_opt];
      }
    }
  }
}

/**
 * MunkiReport 5.6 implementation of url() converted to a wrapper around Laravel url()
 *
 * @param string $url
 * @param bool $fullurl
 * @param array $queryArray
 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|\Illuminate\Foundation\Application|string
 */
function mr_url($url = '', $fullurl = false, $queryArray = [])
{
    $s = $fullurl ? conf('webhost') : '';
    $index_page = conf('index_page');
    $s .= conf('subdirectory').($url && $index_page ? $index_page.'/' : $index_page) . ltrim($url, '/');
    if($queryArray){
        $s .= ($index_page ? '&amp;' : '?') .http_build_query($queryArray, '', '&amp;');
    }

    // There isn't a way to tell Laravel not to generate a fully qualified URL.
    $l_url = url($url, $queryArray);

    //return $s;
    return $l_url;
}

/**
 * Retrieve Remote IP
 *
 * Take Proxy headers into account
 *
 * @return string IP address
 */
function getRemoteAddress()
{
    return request()->getClientIp();
}

/**
 * Return a secure url
 *
 * @param string url
 * @return string secure url
 * @author
 **/
function mr_secure_url($url = '')
{
    $parse_url = parse_url(mr_url($url, true));
    $parse_url['scheme'] = 'https';

    return
         ((isset($parse_url['scheme'])) ? $parse_url['scheme'] . '://' : '')
        .((isset($parse_url['user'])) ? $parse_url['user']
        .((isset($parse_url['pass'])) ? ':' . $parse_url['pass'] : '') .'@' : '')
        .((isset($parse_url['host'])) ? $parse_url['host'] : '')
        .((isset($parse_url['port'])) ? ':' . $parse_url['port'] : '')
        .((isset($parse_url['path'])) ? $parse_url['path'] : '')
        .((isset($parse_url['query'])) ? '?' . $parse_url['query'] : '')
        .((isset($parse_url['fragment'])) ? '#' . $parse_url['fragment'] : '')
        ;
}

/**
 * Lookup group id for passphrase
 *
 * @return integer group id
 * @author AvB
 **/
function passphrase_to_group($passphrase)
{
    $machine_group = new Machine_group;
    if ($machine_group->retrieveOne('property=? AND value=?', array('key', $passphrase))) {
        return $machine_group->groupid;
    }

    return 0;
}

/**
 * Convert an array of values to a key => value array
 * [a, b] is converted to [a => b]
 *
 * @return array assoc array
 * @author
 **/
function arrayToAssoc($array)
{
    if(count($array) % 2){
      throw new \Exception("Cannot convert array: array is of uneven length", 1);
    }
    $result = [];
    while (count($array)) {
        list($key, $value) = array_splice($array, 0, 2);
        $result[$key] = $value;
    }
    return $result;
}

/**
 * Convert a key => value array to an array of values
 * [a => b] is converted to [a, b]
 *
 * @return array array
 * @author
 **/
function assocToArray($array)
{
    $result = [];
    foreach($array as $k => $v)
    {
      $result[] = $k;
      $result[] = $v;
    }
    return $result;
}

/**
 * Check if a user is authorized.
 *
 *
 *
 * @param string $what The action or item that the user should be authorized to perform. can be optional.
 * @return bool
 */
function authorized($what)
{
    if (!Str::contains(config('auth.methods'), 'NOAUTH')) {
        return Auth::check();
    } else {
        return true; // NOAUTH is enabled.
    }

    // Laravel does CSRF Verification

    // Check for a specific authorization item
//    if ($what) {
//        foreach (conf('authorization', array()) as $item => $roles) {
//            if ($what === $item) {
//                // Check if there is a matching role
//                if (in_array($_SESSION['role'], $roles)) {
//                    return true;
//                }
//
//                // Role not found: unauthorized!
//                return false;
//            }
//        }
//    }

    // There is no matching rule, you're authorized!
//    return true;
}

/**
 * Check if current user may access data for serial number
 *
 * @return boolean TRUE if authorized
 * @author
 **/
function authorized_for_serial($serial_number)
{
    // Make sure the reporting script is authorized
    if (isset($GLOBALS['auth']) && $GLOBALS['auth'] == 'report') {
        return true;
    }

//    $user = new User;
//    return $user->canAccessMachineGroup(get_machine_group($serial_number));
    return true;
}

/**
 * Get machine_group
 *
 * @return integer computer group
 * @author AvB
 **/
function get_machine_group($serial_number = '')
{
    if (! isset($GLOBALS['machine_groups'][$serial_number])) {
        
        $machine_group = Reportdata_model::select('machine_group')
            ->where('serial_number', $serial_number)
            ->pluck('machine_group')
            ->first();
        $GLOBALS['machine_groups'][$serial_number] = $machine_group;
    }

    return $GLOBALS['machine_groups'][$serial_number];
}

/**
 * Get filter for machine_group membership
 *
 * @var string optional prefix default 'WHERE'
 * @var string how to address the reportdata table - default 'reportdata'
 * @return string filter clause
 * @author
 **/
function get_machine_group_filter($prefix = 'WHERE', $reportdata = 'reportdata')
{
    $sql = '';
    // Get filtered groups
    if ($groups = get_filtered_groups()) {
        $sql = sprintf(' %s %s.machine_group IN (%s) ', $prefix, $reportdata, implode(', ', $groups));
        $prefix = 'AND';
    }

    if(is_archived_filter_on()){
        $sql .= sprintf(' %s %s.archive_status = 0 ', $prefix, $reportdata);
    }elseif( is_archived_only_filter_on() ){
        $sql .= sprintf(' %s %s.archive_status != 0 ', $prefix, $reportdata);
    }

    return $sql;
}

/**
 * Get filtered groups
 *
 * Laravel Conversion Notes:
 *
 * These $_SESSION global variables won't necessarily be populated in the same way, and should not be accessed directly
 * through the $_SESSION superglobal anyway - mosen.
 *
 * @return void
 * @author
 **/
function get_filtered_groups()
{
    $out = array(0);

    // Get filter
    if(session()->has('filter.machine_group')){
        $out = array_diff(
            session()->get('machine_groups', []),
            session()->get('filter.machine_group')
        );
    }else{
        $out = session()->get('machine_groups', []);
    }

    // If out is empty, signal no groups
    if (! $out) {
        $out[] = -1;
    }

    return $out;
}

// Return if session filter is not set or archived filter is not empty
function is_archived_filter_on() {
    return !request()->session()->has('filter.archived') ||
        request()->session()->get('filter.archived');
}

function is_archived_only_filter_on() {
    return request()->session()->has('filter.archived_only') &&
            request()->session()->get('filter.archived_only');
}

/**
 * Store event for client
 *
 * @param string $serial serial number
 * @param string $module reporting module
 * @param string $type info, error
 * @param string $msg long message
 **/
function store_event($serial, $module = '', $type = '', $msg = 'no_message', $data = '')
{
    Event_model::updateOrCreate(
        [
            'serial_number' => $serial,
            'module' => $module,
        ],
        [
            'type' => $type,
            'msg' => $msg,
            'data' => $data,
            'timestamp' => time(),
        ]
    );
}

/**
 * Delete event for client
 *
 * @param string $serial_number serial number
 * @param string $module reporting module
 **/
function delete_event($serial_number, $module = '')
{
    if (! authorized_for_serial($serial_number)) {
        return false;
    }

    $where[] = ['serial_number', $serial_number];
    if ($module) {
        $where[] = ['module', $module];
    }

    return Event_model::where($where)->delete();
}

/**
 * Truncate a string to a maximum length
 *
 * @deprecated replaced by Str::limit
 * @param $string
 * @param int $limit
 * @param string $pad
 * @return string
 */
function truncate_string($string, $limit = 100, $pad = "...")
{
    return Str::limit($string, $limit, $pad);
}

/**
 * Backwards compatible wrapper to retrieve Modules singleton from the DI container.
 *
 * @deprecated Use Laravel Dependency Injection if possible
 * @return munkireport\lib\Modules
 */
function getMrModuleObj()
{
    return app(Modules::class);
}

/**
 * Backwards compatible wrapper to retrieve Dashboard singleton from the DI container.
 *
 * @deprecated Use Laravel Dependency Injection if possible
 * @return munkireport\lib\Dashboard
 */
function getDashboard()
{
    return app(Dashboard::class);
}

/**
 * Backwards compatible method to get the CSRF token.
 *
 * Wrapper for Laravel's `csrf_token()` helper.
 *
 * @return string
 */
function getCSRF()
{
    return csrf_token();
}


function jsonError($msg = '', $status_code = 400, $exit = true)
{
    jsonView(['error' => $msg], $status_code, $exit);
}

/**
 * @param string $msg The message body which will be encoded as JSON
 * @param int $status_code The status code that should be sent in the response body AND header
 * @param bool $exit If true, exit right after emitting response.
 * @param bool $return If true, return response object rather than emitting response (For Laravel Controllers)
 * @return \Illuminate\Http\JsonResponse|object
 */
function jsonView($msg = '', $status_code = 200, $exit = false, $return = false)
{
    $response = response()->json($msg)->setStatusCode($status_code);

    if(is_array($msg) && isset($msg['error']) && $msg['error'] && $status_code == 200) {
        $response = $response->setStatusCode(400);
    }



    // Check for error, adjust status code if necessary
//    if(is_array($msg) && isset($msg['error']) && $msg['error'] && $status_code == 200){
//        $status_code = 400;
//    }
//
//    mr_view('json', ['msg' => $msg, 'status_code' => $status_code]);

    if ($return) {
        return $response;
    } else {
        $response->send();
    }

    if ($exit) exit;

}

/**
 * Render and emit a KISSMVC style View.
 *
 * @depreacted You should try to use the Laravel view() helper instead, which still uses KISSMVC view for any file not ending in
 * .blade.php
 *
 * @param string $file
 * @param string $vars
 * @param string $view_path
 */
function mr_view($file = '', $vars = '', $view_path = '')
{
    $obj = new View();
    $obj->view($file, $vars, $view_path);
}

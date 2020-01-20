<?php

use munkireport\models\Machine_group, munkireport\lib\Modules, munkireport\lib\Dashboard;

// Munkireport version (last number is number of commits)
$GLOBALS['version'] = '5.1.5.3921';

// Return version without commit count
function get_version()
{
    return preg_replace('/(.*)\.\d+$/', '$1', $GLOBALS['version']);
}

//===============================================
// Uncaught Exception Handling
//===============================================s
function uncaught_exception_handler($e)
{
    // Dump out remaining buffered text
    if (ob_get_level()) {
        ob_end_clean();
    }

  // Get error message
    error('Uncaught Exception: '.$e->getMessage());

  // Write footer
    die(View::doFetch(conf('view_path').'partials/foot.php'));
}

function custom_error($msg = '')
{
    $vars['msg']=$msg;
    die(View::doFetch(APP_PATH.'errors/custom_error.php', $vars));
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

function getdbh()
{
    if (! isset($GLOBALS['dbh'])) {
        try {
            $conn = conf('connection');
            if($conn['options']){
                $conn['options'] = arrayToAssoc($conn['options']);
            }
            switch ($conn['driver']) {
                case 'sqlite':
                    $dsn = "sqlite:{$conn['database']}";
                    break;

                case 'mysql':
                    $dsn = "mysql:host={$conn['host']};port={$conn['port']};dbname={$conn['database']}";
                    if( empty($conn['options'])){
                      add_mysql_opts($conn);
                    }
                    break;

                default:
                    throw new \Exception("Unknown driver in config", 1);
            }
            $GLOBALS['dbh'] = new PDO(
                $dsn,
                $conn['username'],
                $conn['password'],
                $conn['options']
            );
        } catch (PDOException $e) {
            fatal('Connection failed: '.$e->getMessage());
        }

        // Set error mode
        $GLOBALS['dbh']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Store database name in config array
        if (preg_match('/.*dbname=([^;]+)/', conf('pdo_dsn'), $result)) {
            $GLOBALS['conf']['dbname'] = $result[1];
        }
    }
    return $GLOBALS['dbh'];
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

function dumpQuery($queryobj){
    dd(
        vsprintf(
            str_replace(['?'], ['\'%s\''], $queryobj->toSql()),
            $queryobj->getBindings()
        )
    );
}

function add_mysql_opts(&$conn){
  $conn['options'] = [
    PDO::MYSQL_ATTR_INIT_COMMAND => sprintf('SET NAMES %s COLLATE %s', $conn['charset'], $conn['collation'])
  ];
  if($conn['ssl_enabled']){
    foreach(['key', 'cert', 'ca', 'capath', 'cipher'] as $ssl_opt){
      if($conn['ssl_'. $ssl_opt]){
        $conn['options'][constant('PDO::MYSQL_ATTR_SSL_'.strtoupper($ssl_opt))] = $conn['ssl_'. $ssl_opt];
      }
    }
  }
}

//===============================================
// Autoloading for Business Classes
//===============================================
// module classes end with _model
function munkireport_autoload($classname)
{
    // Switch to lowercase filename for models
    $lowercaseClassname = strtolower($classname);

    if (substr($lowercaseClassname, -6) == '_model') {
        $module = substr($lowercaseClassname, 0, -6);
        if( ! getMrModuleObj()->getmoduleModelPath($module, $model)){
            throw new Exception("Cannot load model: ".$classname, 1);
        }
        require_once($model);
    }
}

function url($url = '', $fullurl = false, $queryArray = [])
{
    $s = $fullurl ? conf('webhost') : '';
    $index_page = conf('index_page');
    $s .= conf('subdirectory').($url && $index_page ? $index_page.'/' : $index_page) . ltrim($url, '/');
    if($queryArray){
        $s .= ($index_page ? '&amp;' : '?') .http_build_query($queryArray, '', '&amp;');
    }
    return $s;
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
    if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }

    return $_SERVER['REMOTE_ADDR'];
}
/**
 * Return a secure url
 *
 * @param string url
 * @return string secure url
 * @author
 **/
function secure_url($url = '')
{
    $parse_url = parse_url(url($url, true));
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

function redirect($uri = '', $method = 'location', $http_response_code = 302)
{
    if (! preg_match('#^https?://#i', $uri)) {
        $uri = url($uri);
    }
    switch ($method) {
        case 'refresh':
            header("Refresh:0;url=".$uri);
            break;
        default:
            header("Location: ".$uri, true, $http_response_code);
            break;
    }
    exit;
}

/**
 * Get $_POST variable without error
 *
 * @return string post value
 **/
function post($what = '', $alt = '')
{
    if (isset($_POST[$what])) {
        return $_POST[$what];
    }

    return $alt;
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
 * Generate GUID
 *
 * @return string guid
 * @author
 **/
function get_guid()
{
    return sprintf(
        '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
        mt_rand(0, 65535),
        mt_rand(0, 65535),
        mt_rand(0, 65535),
        mt_rand(16384, 20479),
        mt_rand(32768, 49151),
        mt_rand(0, 65535),
        mt_rand(0, 65535),
        mt_rand(0, 65535)
    );
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

    return id_in_machine_group(get_machine_group($serial_number));
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
        if ($machine_group) {
            $GLOBALS['machine_groups'][$serial_number] = $machine_group;
        } else {
            $GLOBALS['machine_groups'][$serial_number] = 0;
        }
    }

    return $GLOBALS['machine_groups'][$serial_number];
}

/**
 * Check if machine is member of machine_groups of current user
 * if admin, return TRUE
 * otherwise return FALSE
 *
 * @return void
 * @author
 **/
function id_in_machine_group($id)
{
    if ($_SESSION['role'] == 'admin') {
        return true;
    }

    if (isset($_SESSION['machine_groups'])) {
        return in_array($id, $_SESSION['machine_groups']);
    }

    return false;
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

    // Get filtered groups
    if ($groups = get_filtered_groups()) {
        return sprintf(' %s %s.machine_group IN (%s) ', $prefix, $reportdata, implode(', ', $groups));
    } else // No filter
    {
        return '';
    }
}

/**
 * Get filtered groups
 *
 * @return void
 * @author
 **/
function get_filtered_groups()
{
    $out = array();

    // Get filter
    if (isset($_SESSION['filter']['machine_group']) && $_SESSION['filter']['machine_group']) {
        $filter = $_SESSION['filter']['machine_group'];
        $out = array_diff($_SESSION['machine_groups'], $filter);
    } else {
        $out = $_SESSION['machine_groups'];
    }

    // If out is empty, signal no groups
    if (! $out) {
        $out[] = -1;
    }

    return $out;
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

// Truncate string
function truncate_string($string, $limit = 100, $pad = "...")
{
    if (strlen($string) <= $limit) {
        return $string;
    }

    return substr($string, 0, $limit - strlen($pad)) . $pad;
}

// Create a singleton moduleObj
function getMrModuleObj()
{
    static $moduleObj;

    if( ! $moduleObj){
      $moduleObj = new Modules;
    }

    return $moduleObj;
}

// Create a singleton dashboard
function getDashboard()
{
    static $dashboardObj;

    if( ! $dashboardObj){
      $dashboardObj = new Dashboard(conf('dashboard'));
    }

    return $dashboardObj;
}

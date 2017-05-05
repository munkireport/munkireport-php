<?php

// Munkireport version (last number is number of commits)
$GLOBALS['version'] = '2.14.0.2749';

// Return version without commit count
function get_version()
{
    return preg_replace('/(.*)\.\d+$/', '$1', $GLOBALS['version']);
}

//===============================================
// Legacy support
//===============================================s
if (version_compare(phpversion(), '5.5.0', '<')) {
    include 'php_legacy_helper.php';
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
            $GLOBALS['dbh'] = new PDO(
                conf('pdo_dsn'),
                conf('pdo_user'),
                conf('pdo_pass'),
                conf('pdo_opts')
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

//===============================================
// Autoloading for Business Classes
//===============================================
// module classes end with _model
function __autoload($classname)
{
    // Switch to lowercase filename for models
    $lowercaseClassname = strtolower($classname);

    if (substr($lowercaseClassname, -4) == '_api') {
        require_once(APP_PATH.'modules/'.substr($lowercaseClassname, 0, -4).'/api'.EXT);
    } elseif (substr($lowercaseClassname, -6) == '_model') {
        $module = substr($lowercaseClassname, 0, -6);
        if( ! getMrModuleObj()->getmoduleModelPath($module, $model)){
            throw new Exception("Cannot load model: ".$classname, 1);
        }
        require_once($model);
    }  elseif (strpos($classname, 'munkireport\\lib') === 0){
        require_once(APP_PATH.'lib/munkireport/'.str_replace('munkireport\\lib\\', '', $classname).EXT);
    } elseif (strpos($classname, 'munkireport\\controller\\') === 0){
        $controller = str_replace('munkireport\\controller\\', '', $classname);
        if ( ! preg_match('#^[A-Za-z0-9_-]+$#', $controller)){
            throw new Exception("Illegal controller name: ".$controller, 1);
        }
        if( ! file_exists(CONTROLLER_PATH.$controller.EXT)){
            throw new Exception("Controller does not exist: $controller", 1);
        }
        require_once CONTROLLER_PATH.$controller.EXT;
    } elseif ($classname == 'Hautelook\\Phpass\\PasswordHash'){
        require(APP_PATH . '/lib/phpass-0.3.5/src/Hautelook/Phpass/PasswordHash.php');
    }
    else {
        require_once(APP_PATH.'models/'.$lowercaseClassname.EXT);
    }
}

function url($url = '', $fullurl = false)
{
    $s = $fullurl ? conf('webhost') : '';
    $s .= conf('subdirectory').($url && INDEX_PAGE ? INDEX_PAGE.'/' : INDEX_PAGE) . ltrim($url, '/');
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
        $reportdata = new Reportdata_model;
        if ($reportdata->retrieveOne('serial_number=?', $serial_number)) {
            $GLOBALS['machine_groups'][$serial_number] = $reportdata->machine_group;
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
        return sprintf('%s %s.machine_group IN (%s)', $prefix, $reportdata, implode(', ', $groups));
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
    $evtobj = new Event_model($serial, $module);
    $evtobj->store($type, $msg, $data);
}

/**
 * Delete event for client
 *
 * @param string $serial serial number
 * @param string $module reporting module
 **/
function delete_event($serial, $module = '')
{
    $evtobj = new Event_model();
    $evtobj->reset($serial, $module);
}


// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.
function truncate_string($string, $limit = 100, $break = ".", $pad = "...")
{
  // return with no change if string is shorter than $limit
    if (strlen($string) <= $limit) {
        return $string;
    }

  // is $break present between $limit and the end of the string?
    if (false !== ($breakpoint = strpos($string, $break, $limit))) {
        if ($breakpoint < strlen($string) - 1) {
            $string = substr($string, 0, $breakpoint) . $pad;
        }
    }

    return $string;
}

// Create a singleton moduleObj
function getMrModuleObj()
{
    static $moduleObj;

    if( ! $moduleObj){
      $moduleObj = new munkireport\lib\Modules;
    }

    return $moduleObj;
}

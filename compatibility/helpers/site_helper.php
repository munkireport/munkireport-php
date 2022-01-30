<?php

use App\Notifications\GeneralEvent;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use munkireport\models\Machine_group, munkireport\lib\Modules, munkireport\lib\Dashboard;
use Compatibility\Kiss\View;

// Munkireport version (last number is number of commits)
$GLOBALS['version'] = '6.0.0.4389';

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
 * @param string $msg alert message
 * @param string $type type (danger, warning, success, info)
 * @deprecated use session()->flash() functionality from Laravel
 **/
function alert(string $msg, string $type = "info"): void
{
    Log::channel('deprecations')
        ->warning(
            'This function will be deprecated in future, please use session()->flash() or similar in your modules',
            [
                'function' => __FUNCTION__,
                'file' => __FILE__,
                'line' => __LINE__,
            ]
        );
    $GLOBALS['alerts'][$type][] = $msg;
    request()->session()->flash($type, $msg);
}

/**
 * Add error message
 *
 * @param string $msg message
 * @param string $i18n i18n locale key for translation of error.
 * @deprecated use session()->flash() functionality from Laravel
 **/
function error(string $msg, $i18n = ''): void
{
    Log::channel('deprecations')
        ->warning(
            'This function will be deprecated in future, please use session()->flash() or similar in your modules',
            [
                'function' => __FUNCTION__,
                'file' => __FILE__,
                'line' => __LINE__,
            ]
        );
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
 * This function is used to get a database handle from PDO, mostly from KissMvc models that use the Schema functions
 * prior to Eloquent Capsule. It should not be used anymore.
 *
 * @return PDO
 * @throws \PDOException
 * @deprecated Please use the DB query builder if you need a connnection handle, or use Eloquent ORM for queries.
 */
function getdbh(): PDO
{
    Log::channel('deprecations')
        ->warning(
            'This function will be deprecated in future, please use db query builder or eloquent.',
            [
                'function' => __FUNCTION__,
                'file' => __FILE__,
                'line' => __LINE__,
            ]
        );
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
    if (isset($connection['driver']) && $connection['driver'] == $driver) {
        return true;
    }
    return false;
}

function add_mysql_opts(&$conn)
{
    $conn['options'] = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => sprintf('SET NAMES %s COLLATE %s', $conn['charset'], $conn['collation'])
    ];
    if ($conn['ssl_enabled']) {
        foreach (['key', 'cert', 'ca', 'capath', 'cipher'] as $ssl_opt) {
            if ($conn['ssl_' . $ssl_opt]) {
                $conn['options'][constant('PDO::MYSQL_ATTR_SSL_' . strtoupper($ssl_opt))] = $conn['ssl_' . $ssl_opt];
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
 * @return string
 * @deprecated Please use the url() helper from the Laravel framework instead.
 */
function mr_url($url = '', $fullurl = false, $queryArray = [])
{
    $s = $fullurl ? conf('webhost') : '';
    $index_page = conf('index_page');
    $s .= conf('subdirectory') . ($url && $index_page ? $index_page . '/' : $index_page) . ltrim($url, '/');
    if ($queryArray) {
        $s .= ($index_page ? '&amp;' : '?') . http_build_query($queryArray, '', '&amp;');
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
 * @todo Still used in `laps` module.
 * @return string IP address
 * @deprecated please use request()->getClientIp()
 */
function getRemoteAddress(): string
{
    return request()->getClientIp();
}

/**
 * Lookup group id for passphrase
 *
 * @return integer group id
 * @author AvB
 **/
function passphrase_to_group($passphrase): int
{
    $machine_group = new Machine_group;
    if ($machine_group->retrieveOne('property=? AND value=?', array('key', $passphrase))) {
        return $machine_group->groupid;
    }

    return 0;
}

/**
 * Check if a user is authorized.
 *
 * @param ?string $what The authorization name to check for, which is a key present in the config
 *                     _munkireport.authorization. Usually delete_machine, global, or archive.
 * @return bool true if the user is authorized to perform the action.
 * @deprecated Please use the available gates for RBAC 'global', 'delete_machine', 'archive' etc, or Policy for ABAC.
 */
function authorized(?string $what = null)
{
    if (!Auth::check()) {
        return false; // User is not logged in at all
    }

    // Laravel does CSRF Verification, so that no longer applies.

    if ($what !== null) {
        switch ($what) {
            case 'global':
                return Gate::allows('global');
            case 'delete_machine':
                return Gate::allows('delete_machine');
            case 'archive':
                return Gate::allows('archive');
            case '': // some modules are silly and pass through an empty string when they mean null
                return true;
            default:
                Log::warning(
                    'attempted to check authorization for action: ' . $what . ', which does not exist as a Gate'
                );
                return false;
        }
    } else {
        return true;
    }
}

/**
 * Check if current user may access data for serial number
 *
 * @return boolean TRUE if authorized
 * @author
 * @todo This has not been converted to Laravel at all, the functionality is missing.
 **/
function authorized_for_serial(string $serial_number)
{
    // Make sure the reporting script is authorized
    if (isset($GLOBALS['auth']) && $GLOBALS['auth'] == 'report') {
        return true;
    }

    // TODO: needs new BU membership check

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
function get_machine_group(string $serial_number = '')
{
    if (!isset($GLOBALS['machine_groups'][$serial_number])) {
        $machine_group = \App\ReportData::select('machine_group')
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
 * @return string filter clause
 * @param string $reportdata how to address the reportdata table - default 'reportdata'
 * @param string $prefix optional prefix default 'WHERE'
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

    if (is_archived_filter_on()) {
        $sql .= sprintf(' %s %s.archive_status = 0 ', $prefix, $reportdata);
    } elseif (is_archived_only_filter_on()) {
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
 **/
function get_filtered_groups(): array
{
    $out = array(0);

    // Get filter
    if (session()->has('filter.machine_group')) {
        $out = array_diff(
            session()->get('machine_groups', []),
            session()->get('filter.machine_group')
        );
    } else {
        $out = session()->get('machine_groups', []);
    }

    // If out is empty, signal no groups
    if (!$out) {
        $out[] = -1;
    }

    return $out;
}

// Return if session filter is not set or archived filter is not empty
function is_archived_filter_on()
{
    return !request()->session()->has('filter.archived') || request()->session()->get('filter.archived');
}

function is_archived_only_filter_on()
{
    return request()->session()->has('filter.archived_only') && request()->session()->get('filter.archived_only');
}

/**
 * Store event for client
 *
 * @param string $serial Serial number of the machine reporting in.
 * @param string $module The module which raised the event (in some cases the database table - in KISS MVC).
 * @param string $type The category or severity of the event.
 * @param string $msg A plaintext message explaining the event, or a magic string indicating a value in a locale to display
 *                    a localized version of that event message.
 * @param string $data A JSON serialized dictionary of additional data to store with the event that may provide more
 *                     context.
 * @param bool $raise_notification If event forwarding is turned on, a notification will be raised for every event, but
 *                                 in specific circumstances you don't want a notification raised. set to false if you
 *                                 *REALLY* do not want a notification raised here.
 */
function store_event(
    string $serial,
    string $module = '',
    string $type = '',
    string $msg = 'no_message',
    string $data = '',
    $raise_notification = true
)
{
    if (config('_munkireport.notifications.forward_events', true) && $raise_notification) {
        Log::debug(
            'forwarding munkireport events to notification channel(s) because notifications.forward_events = true'
        );
        Notification::send(User::all(), new GeneralEvent($serial, $module, $type, $msg, $data));
    }
    \App\Event::updateOrCreate(
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
    if (!authorized_for_serial($serial_number)) {
        return false;
    }

    $where[] = ['serial_number', $serial_number];
    if ($module) {
        $where[] = ['module', $module];
    }

    return \App\Event::where($where)->delete();
}

/**
 * Truncate a string to a maximum length
 *
 * @param string $string
 * @param int $limit
 * @param string $pad
 * @return string
 * @deprecated replaced by Str::limit
 */
function truncate_string($string, $limit = 100, $pad = "...")
{
    return Str::limit($string, $limit, $pad);
}

/**
 * Backwards compatible wrapper to retrieve Modules singleton from the DI container.
 *
 * @return munkireport\lib\Modules
 * @deprecated Use Laravel Dependency Injection if possible
 */
function getMrModuleObj(): Modules
{
    return app(Modules::class);
}

/**
 * Backwards compatible wrapper to retrieve Dashboard singleton from the DI container.
 *
 * @return munkireport\lib\Dashboard
 * @deprecated Use Laravel Dependency Injection if possible
 */
function getDashboard(): Dashboard
{
    return app(Dashboard::class);
}

/**
 * Backwards compatible method to get the CSRF token.
 *
 * Wrapper for Laravel's `csrf_token()` helper.
 *
 * @return string The current CSRF token
 * @deprecated Use the csrf_token() helper directly if possible.
 * @todo Cannot be removed, still in warranty admin module.
 */
function getCSRF(): string
{
    return csrf_token();
}

/**
 * Display a JSON error.
 *
 * @param string $msg
 * @param int $status_code
 * @param bool $exit
 * @deprecated Use native exception handling for json responses or render a blade view.
 * @todo Still used in `localadmin` module, cannot be fully deprecated.
 */
function jsonError(string $msg = '', int $status_code = 400, bool $exit = true): void
{
    jsonView(['error' => $msg], $status_code, $exit);
}

/**
 * Render a JSON response (with KissMvc Framework View)
 *
 * @param string|array $msg The message body which will be encoded as JSON, or an array that can be json_encode()'d
 * @param int $status_code The status code that should be sent in the response body AND header
 * @param bool $exit If true, exit right after emitting response.
 * @param bool $return If true, return response object rather than emitting response (For Laravel Controllers)
 * @return \Illuminate\Http\JsonResponse|object|null
 * @deprecated please use response()->json() instead if possible.
 * @todo Cannot remove this until applications, disk_report, managedinstalls, and power are updated.
 */
function jsonView($msg = '', int $status_code = 200, bool $exit = false, bool $return = false)
{
    $response = response()->json($msg, $status_code);

    if (is_array($msg) && isset($msg['error']) && $msg['error'] && $status_code == 200) {
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

    if ($exit) {
        exit;
    }

    return null;
}

/**
 * Render and emit a KISSMVC style View.
 *
 * @deprecated You should try to use the Laravel view() helper instead, which still uses KISSMVC view for any file not ending in
 * .blade.php
 *
 * @param string $file The .php view file to render, relative to the view_path
 * @param string|array $vars The variables to pass to the view. If empty string, no variables are passed.
 * @param string $view_path The base view path to search for the $file parameter in. If left empty, it will be the default view path.
 * @return void Outputs the view directly into the output buffer, nothing is returned.
 */
function mr_view(string $file = '', $vars = '', string $view_path = ''): void
{
    $obj = new View();
    $obj->view($file, $vars, $view_path);
}


/**
 * Render a KISSMVC style View into the output buffer then capture it as a string.
 *
 * @deprecated You should try to use the Laravel view() helper instead, which still uses KISSMVC view for any file not ending in
 * .blade.php
 *
 * @param string $file
 * @param array $vars
 * @param string $view_path
 * @return string
 */
function mr_view_output(string $file = '', array $vars = [], string $view_path = ''): string
{
    $obj = new View();
    return $obj->viewFetch($file, $vars, $view_path);
}

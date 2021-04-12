<?php
namespace App\Http\Controllers;

use DateTime;
use MR\Kiss\View;
use App\ReportData;
use Symfony\Component\Yaml\Yaml;

/**
 * Reportdata module class
 *
 * @package munkireport
 * @author AvB
 **/
class ReportDataController extends Controller
{
    // TODO: Apply CORS at middleware/route layer
    // header('Access-Control-Allow-Origin: *');


    public function report(string $serial_number)
    {
        jsonView(
            ReportData::where('serial_number', $serial_number)
                ->filter('groupOnly')
                ->first()
        );
    }

    /**
     * Get machine groups
     *
     * @author
     **/
    public function get_groups()
    {
        $result = ReportData::selectRaw('machine_group, COUNT(*) AS cnt')
            ->groupBy('machine_group')
            ->get()
            ->toArray();
        $obj = new View();
        $obj->view('json', array('msg' => $result));
    }

    /**
     * Get inactive days from config
     *
     **/
    public function get_inactive_days()
    {
        $obj = new View();
        $obj->view('json', [
            'msg' => ['inactive_days' => config('reportdata.days_inactive')]
        ]);
    }
    
    /**
     * Get check-in statistics
     *
     **/
    public function get_lastseen_stats()
    {
        $inactive_days = config('reportdata.days_inactive');
        $now = time();
        $hour_ago = $now - 3600;
        $today = strtotime('today');
        $week_ago = $now - 3600 * 24 * 7;
        $month_ago = $now - 3600 * 24 * 30;
        $three_month_ago = $now - 3600 * 24 * 90;
        $custom_ago = $now - 3600 * 24 * $inactive_days;
        $reportdata = ReportData::selectRaw("COUNT(1) as total,
                COUNT(CASE WHEN timestamp > $hour_ago THEN 1 END) AS lasthour,
                COUNT(CASE WHEN timestamp > $today THEN 1 END) AS today,
                COUNT(CASE WHEN timestamp > $week_ago THEN 1 END) AS lastweek,
                COUNT(CASE WHEN timestamp > $month_ago THEN 1 END) AS lastmonth,
                COUNT(CASE WHEN timestamp BETWEEN $month_ago AND $week_ago THEN 1 END) AS inactive_week,
                COUNT(CASE WHEN timestamp > $custom_ago THEN 1 END) AS lastcustom,
                COUNT(CASE WHEN timestamp BETWEEN $three_month_ago AND $month_ago THEN 1 END) AS inactive_month,
                COUNT(CASE WHEN timestamp < $three_month_ago THEN 1 END) AS inactive_three_month")
            ->filter()
            ->first();
        $obj = new View();
        $obj->view('json', ['msg' => $reportdata]);
    }
    
    /**
     * Get uptime statistics
     *
     **/
    public function getUptimeStats()
    {
        $reportdata = ReportData::selectRaw('SUM(CASE WHEN uptime <= 86400 THEN 1 END) AS oneday,
                SUM(CASE WHEN uptime BETWEEN 86400 AND 604800 THEN 1 END) AS oneweek,
                SUM(CASE WHEN uptime >= 604800 THEN 1 END) AS oneweekplus')
            ->where('uptime', '>', 0)
            ->filter()
            ->first();

        $obj = new View();
        $obj->view('json', array('msg' => $reportdata));
    }

    /**
     * REST API for retrieving registration dates
     *
     **/
    public function new_clients()
    {
        $db = new \Model();

        $where = get_machine_group_filter('WHERE', 'r');

        switch (conf('connection')['driver']) {
            case 'sqlite':
                $sql = "SELECT strftime('%Y-%m', DATE(reg_timestamp, 'unixepoch')) AS date,
						COUNT(*) AS cnt,
						machine_name AS type
						FROM reportdata r
						LEFT JOIN machine m 
							ON (r.serial_number = m.serial_number)
						$where
						GROUP BY date, machine_name
						ORDER BY date";
                break;
            case 'mysql':
                $sql = "SELECT DATE_FORMAT(DATE(FROM_UNIXTIME(reg_timestamp)), '%Y-%m') AS date, 
						COUNT(*) AS cnt,
						machine_name AS type
						FROM reportdata r
						LEFT JOIN machine m 
							ON (r.serial_number = m.serial_number)
                        $where
						GROUP BY date, machine_name
						ORDER BY date";
                break;
            default:
                die('Unknown database driver');
        }

        $dates = array();
        $out = array();
        
        foreach ($db->query($sql) as $event) {
            // Store date
            $d = new DateTime( $event->date );
            $lastDayOfTheMonth = $d->format( 'Y-m-t' );
            
            // Check if this is the first run
            if( ! $dates){
                // Subtract 16 days and format to last day of the month
                $lastDayOfTheMonthBefore = $d->sub(new \DateInterval('P16D'))->format( 'Y-m-t' );
                array_push($dates, $lastDayOfTheMonthBefore);
            }
            
            $pos = array_search($lastDayOfTheMonth, $dates);
            if ($pos === false) {
                array_push($dates, $lastDayOfTheMonth);
                $pos = count($dates) - 1;
            }

            $out[$event->type][$pos] = intval($event->cnt);
        }
        
        // Sort machine types
        ksort($out);
        
        // Replace last date with current date
        if(array_pop($dates)){
            array_push($dates, date('Y-m-d'));
        }
        
        // Prepend all types with 0
        foreach($out as $type => $data)
        {
            $out[$type][0] = 0;
        }

        $obj = new View();
        $obj->view('json', array('msg' => array('dates' => $dates, 'types' => $out)));
    }

    /**
     * Flotr2 interface, returns json with ip address ranges
     * defined in conf('ip_ranges')
     * or passed with GET request
     *
     * @return void
     * @author AvB
     **/
    public function ip()
    {
        
        try {
            $ip_arr = Yaml::parseFile(config('reportdata.ip_config_path'));
        } catch (\Exception $e) {
            // Do something
            $ip_arr = [];
        }

        $out = [];

        // Compile SQL
        $cnt = 0;
        $sel_arr = array('COUNT(1) as count');
        foreach ($ip_arr as $key => $value) {
            if (is_scalar($value)) {
                $value = array($value);
            }
            $when_str = '';
            foreach ($value as $k => $v) {
                $when_str .= sprintf(" WHEN remote_ip LIKE '%s%%' THEN 1", $v);
            }
            $sel_arr[] = "SUM(CASE $when_str ELSE 0 END) AS r${cnt}";
            $cnt++;
        }
        $sql = "SELECT " . implode(', ', $sel_arr) . "
				FROM reportdata "
                .get_machine_group_filter();

        $reportdata = ReportData::selectRaw(implode(', ', $sel_arr))
            ->filter()
            ->first();
        // Create Out array
        if ($reportdata) {
            $cnt = $total = 0;
            foreach ($ip_arr as $key => $value) {
                $col = 'r' . $cnt++;

                $out[] = array('key' => $key, 'cnt' => intval($reportdata[$col]));

                $total += $reportdata[$col];
            }

            // Add Remaining IP's as other
            if ($reportdata['count'] - $total) {
                $out[] = array('key' => 'Other', 'cnt' => $reportdata['count'] - $total);
            }
        }

        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }
} // END class Reportdata_controller

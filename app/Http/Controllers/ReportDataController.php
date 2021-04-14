<?php
namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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

        return response()->json($result);
    }

    /**
     * Get inactive days from config
     *
     **/
    public function get_inactive_days()
    {
        return response()->json(['inactive_days' => config('reportdata.days_inactive')]);
    }
    
    /**
     * Get check-in statistics
     *
     **/
    public function get_lastseen_stats()
    {
        $inactive_days = config('reportdata.days_inactive');

        $hour_ago = Carbon::now()->subHour();
        $today = Carbon::now()->startOfDay();
        $week_ago = Carbon::now()->subWeek();
        $month_ago = Carbon::now()->subMonth();
        $three_month_ago = Carbon::now()->subMonths(3);
        $custom_ago = Carbon::now()->subDays($inactive_days);

        $reportdata = ReportData::histogramByCase([
            "lasthour" => "COUNT(CASE WHEN timestamp > {$hour_ago->unix()} THEN 1 END)",
            "today" => "COUNT(CASE WHEN timestamp > {$today->unix()} THEN 1 END)",
            "lastweek" => "COUNT(CASE WHEN timestamp > {$week_ago->unix()} THEN 1 END)",
            "lastmonth" => "COUNT(CASE WHEN timestamp > {$month_ago->unix()} THEN 1 END)",
            "inactive_week" => "COUNT(CASE WHEN timestamp BETWEEN {$month_ago->unix()} AND {$week_ago->unix()} THEN 1 END)",
            "lastcustom" => "COUNT(CASE WHEN timestamp > {$custom_ago->unix()} THEN 1 END)",
            "inactive_month" => "COUNT(CASE WHEN timestamp BETWEEN {$three_month_ago->unix()} AND {$month_ago->unix()} THEN 1 END)",
            "inactive_three_month" => "COUNT(CASE WHEN timestamp < {$three_month_ago->unix()} THEN 1 END)",
        ])->filter()->first();

        return response()->json($reportdata);
    }
    
    /**
     * Get uptime statistics
     *
     **/
    public function getUptimeStats()
    {
        $reportdata = ReportData::histogramByCase([
            "oneday" => "SUM(CASE WHEN uptime <= 86400 THEN 1 END)",
            "oneweek" => "SUM(CASE WHEN uptime BETWEEN 86400 AND 604800 THEN 1 END)",
            "oneweekplus" => "SUM(CASE WHEN uptime >= 604800 THEN 1 END)",
        ])->where('uptime', '>', 0)->filter()->first();

        return response()->json($reportdata);
    }

    public function new_clients2()
    {
        $groups = get_filtered_groups();
        $mysqlDateExpression = "DATE_FORMAT(DATE(FROM_UNIXTIME(reg_timestamp)), '%Y-%m') AS date";
        $sqliteDateExpression = "strftime('%Y-%m', DATE(reg_timestamp, 'unixepoch')) AS date";

        $monthlyRegistrationHistogram = DB::table('reportdata')
            ->select('machine_name AS type')
            ->selectRaw('COUNT(*) as cnt')
            ->selectRaw($mysqlDateExpression)
            ->leftJoin(
                'machine',
                'reportdata.serial_number', '=', 'machine.serial_number')
            ->whereIn('machine_group', $groups)
            ->groupBy('date', 'machine_name')
            ->orderBy('date');

        if (is_archived_filter_on()) {
            $monthlyRegistrationHistogram = $monthlyRegistrationHistogram->where('reportdata.archive_status', '=', 0);
        } else if (is_archived_only_filter_on()) {
            $monthlyRegistrationHistogram = $monthlyRegistrationHistogram->where('reportdata.archive_status', '<>', 0);
        }

        $monthlyRegistrationHistogram = $monthlyRegistrationHistogram->get();
        $series = [];

        foreach ($monthlyRegistrationHistogram as $dataPoint) {
            if (!isset($series[$dataPoint->date])) {
                $series[$dataPoint->date] = [];
            }

            if (!isset($series[$dataPoint->date][$dataPoint->type])) {
                $series[$dataPoint->date][$dataPoint->type] = $dataPoint->cnt;
            } else {
                $series[$dataPoint->date][$dataPoint->type] += $dataPoint->cnt;
            }
        }

        // Produce 'dates' output array with one date per array index (last day of the month) and
        // 'types' output array with one machine type as a key, and an array of indices referring to the date in the
        // first variable, with the value being the count of machine registrations on that date.
        // yeah. this is insane.
        $dates = array_keys($series);

        $types = [];
        for ($i = 0; $i < count($dates); $i++) {
            foreach ($series[$dates[$i]] as $type => $count) {
                if (!isset($types[$type])) {
                    $types[$type] = [
                        $i => $count,
                    ];
                } else {
                    $types[$type][$i] = $count;
                }
            }

        }

        // 1. Expected output is the last day of the month
        // 2. If the first element, prepend the last day of the previous month with a fixed value of zero.
        // 3. If its the last element, then it should be moved to the current date instead of the last date of the previous
        // month.
        $adjustedDates = [];
        foreach ($dates as $date) {
            $d = new Carbon($date);
            if ($dates[count($dates) - 1] === $date) {
                $adjustedDates[] = Carbon::now()->format('Y-m-d');
            } else {
                $adjustedDates[] = $d->endOfMonth()->format('Y-m-d');
            }
        }

        return ['dates' => $adjustedDates, 'types' => $types];
    }

    /**
     * REST API for retrieving registration dates
     *
     **/
    public function new_clients()
    {
        $db = new \Model();

        $where = get_machine_group_filter('WHERE', 'r');
        $driver = config('database.connections', [])[config('database.default')]['driver'];
        switch ($driver) {
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

        return response()->json(array('dates' => $dates, 'types' => $out));
    }

    /**
     * Flotr2 interface, returns json with ip address ranges
     * defined in conf('ip_ranges')
     * or passed with GET request
     *
     * @author AvB
     **/
    public function ip(): JsonResponse
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

        return response()->json($out);
    }
} // END class Reportdata_controller

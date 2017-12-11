<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Reportdata_model extends Eloquent
{

  /**
   * Reset Machine Group attribute
   *
   * @param integer $groupid groupid to reset
   **/
    public function reset_group($groupid = 0)
    {
        $sql = 'UPDATE reportdata SET machine_group = 0 WHERE machine_group = ?';
        $stmt = $this->prepare($sql);
        $this->execute($stmt, array($groupid));
    }

    /**
     * Get machine_groups
     *
     * @return array machine_groups
     * @author AvB
     **/
    public function get_groups($count = false)
    {
        if ($count) {
            $out = array();
        } else {
            $out = array(0 => 0);
        }

        $sql = "SELECT machine_group, COUNT(*) AS cnt
				FROM reportdata
				GROUP BY machine_group";
        foreach ($this->query($sql) as $obj) {
            if ($count) {
                $obj->machine_group = intval($obj->machine_group);
                $obj->cnt = intval($obj->cnt);
                $out[] = $obj;
            } else {
                $out[$obj->machine_group] = $obj->machine_group;
            }
        }

        return $out;
    }
    
    /**
     * Get uptime for Clients
     *
     * Calculate uptime per timeslot
     *
     **/
    public function getUptimeStats()
    {
            $sql = "SELECT SUM(CASE WHEN uptime <= 86400 THEN 1 END) AS oneday,
                    SUM(CASE WHEN uptime BETWEEN 86400 AND 604800 THEN 1 END) AS oneweek,
                    SUM(CASE WHEN uptime >= 604800 THEN 1 END) AS oneweekplus
                    FROM reportdata
                    WHERE uptime > 0
                    ".get_machine_group_filter('AND');
            
            return current($this->query($sql));
    }
    
    
    /**
     * Get check-in statistics
     *
     *
     **/
    public function get_lastseen_stats()
    {
        $now = time();
        $hour_ago = $now - 3600;
        $today = strtotime('today');
        $week_ago = $now - 3600 * 24 * 7;
        $month_ago = $now - 3600 * 24 * 30;
        $three_month_ago = $now - 3600 * 24 * 90;
        $sql = "SELECT COUNT(1) as total, 
        	COUNT(CASE WHEN timestamp > $hour_ago THEN 1 END) AS lasthour, 
        	COUNT(CASE WHEN timestamp > $today THEN 1 END) AS today, 
        	COUNT(CASE WHEN timestamp > $week_ago THEN 1 END) AS lastweek,
        	COUNT(CASE WHEN timestamp > $month_ago THEN 1 END) AS lastmonth,
        	COUNT(CASE WHEN timestamp BETWEEN $month_ago AND $week_ago THEN 1 END) AS inactive_week,
        	COUNT(CASE WHEN timestamp BETWEEN $three_month_ago AND $month_ago THEN 1 END) AS inactive_month,
        	COUNT(CASE WHEN timestamp < $three_month_ago THEN 1 END) AS inactive_three_month
        	FROM reportdata
        	".get_machine_group_filter();

        return(current($this->query($sql)));
    }
}

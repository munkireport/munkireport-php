<?php

use CFPropertyList\CFPropertyList;

class managedinstalls_model extends \Model
{

    public function __construct($serial_number = '')
    {
        parent::__construct('id', 'managedinstalls'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial_number;
        $this->rs['name'] = '';
        $this->rs['display_name'] = '';
        $this->rs['version'] = '';
        $this->rs['size'] = 0;
        $this->rs['installed'] = 0; // 1 = installed, 0 = not installed
        $this->rs['status'] = ''; // installed, pending, failed
        $this->rs['type'] = ''; // munki, applesus

        if ($serial_number) {
            $this->retrieve_record($serial_number);
            $this->serial_number = $serial_number;
        }
    }

    /**
     * Get statistics
     *
     *
     *
     * @param integer $hours hours
     * @return {11:return type}
     */
    public function get_stats($hours = 24)
    {
        if ($hours > 0) {
            $timestamp = time() - 60 * 60 * $hours;
        } else {
            $timestamp = 0;
        }

        $filter = get_machine_group_filter('AND');
        $sql = "SELECT managedinstalls.status, type, count(distinct reportdata.serial_number) as clients, count(managedinstalls.status) as total_items
            FROM reportdata
            LEFT JOIN managedinstalls USING(serial_number)
            WHERE reportdata.timestamp > $timestamp
            AND managedinstalls.type IS NOT NULL
            $filter
            GROUP BY managedinstalls.status,  managedinstalls.type";

        return $this->query($sql);
    }

    // ------------------------------------------------------------------------

    /**
     * Get pending installs
     *
     *
     * @param int $hours Amount of hours to look back in history
     **/
    public function get_pending_installs($type, $hoursBack)
    {
        $fromdate = time() - 3600 * $hoursBack;
        $updates_array = array();
        $filter = get_machine_group_filter('AND');
        $sql = "SELECT m.name, m.display_name, m.version, count(*) as count
                FROM managedinstalls m
                LEFT JOIN reportdata USING (serial_number)
                WHERE status = 'pending_install'
                AND type = ?
                $filter
                AND reportdata.timestamp > $fromdate
                GROUP BY name, display_name, version
                ORDER BY count DESC";
        return $this->query($sql, array($type));
    }


    // ------------------------------------------------------------------------

    /**
     * Get package statistics
     *
     * Get statistics about a packat
     *
     * @param string name Package name or nothing for all packages
     * @return {11:return type}
     */
    public function get_pkg_stats($pkg = '')
    {
        $where = '';
        $bindings = array();

        if ($pkg) {
            $where = 'AND m.name = ?';
            $bindings[] = $pkg;
        }

        $filter = get_machine_group_filter();
        $sql = "SELECT m.name, m.version, m.display_name, m.status, count(*) as count
            FROM reportdata
            LEFT JOIN managedinstalls m USING(serial_number)
            $filter
            $where
            GROUP BY m.status, m.name, m.display_name, m.version
            ORDER BY m.version DESC";
        return $this->query($sql, $bindings);
    }
    // ------------------------------------------------------------------------

    /**
     * Setter for serial_number
     */
    public function setSerialNumber($serial_number)
    {
        $this->serial_number = $serial_number;
    }

    // ------------------------------------------------------------------------

    /**
     * Get machines with pending installs
     *
     *
     * @param int $hours Amount of hours to look back in history
     **/
    public function get_clients($status, $hours = 24)
    {
        $timestamp = time() - 60 * 60 * $hours;
        $out = array();
        $filter = get_machine_group_filter('AND');
        $sql = "SELECT computer_name, count(*) as count, reportdata.serial_number
            FROM reportdata
            LEFT JOIN managedinstalls USING(serial_number)
            LEFT JOIN machine USING(serial_number)
            WHERE status = ?
            $filter
            AND reportdata.timestamp > $timestamp
            GROUP BY reportdata.serial_number, computer_name
            ORDER BY count DESC";

        return $this->query($sql, array($status));
    }



    // ------------------------------------------------------------------------

    /**
     * Process data sent by postflight
     *
     * @param string data property list
     *
     **/
    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data, CFPropertyList::FORMAT_XML);
        $mylist = $parser->toArray();
        if ($mylist) {
            // Run processData
            $this->processData($mylist);
        }
    }

    /**
     * Process Data
     *
     * Process data provided
     *
     * @param array $mylist array with entries
     */
    public function processData($mylist)
    {

        // Remove previous data
        $this->deleteWhere('serial_number=?', $this->serial_number);

        // List with fillable entries
        $fillable = array(
            'name' => '',
            'display_name' => '',
            'version' => '',
            'size' => 0,
            'installed' => 0,
            'status' => '',
            'type' => '',
        );

        $new_installs = array();
        $uninstalls = array();

        # Loop through list
        foreach ($mylist as $name => $props) {
            // Get an instance of the fillable array
            $temp = $fillable;

            // Add name to temp
            $temp['name'] = $name;

            // Copy values and correct type
            foreach ($temp as $key => $value) {
                if (array_key_exists($key, $props)) {
                    $temp[$key] = $props[$key];
                    settype($temp[$key], gettype($value));
                }
            }

            // Set version
            if (isset($props['installed_version'])) {
                $temp['version'] = $props['installed_version'];
            } elseif (isset($props['version_to_install'])) {
                $temp['version'] = $props['version_to_install'];
            }

            // Set installed size
            if (isset($props['installed_size'])) {
                $temp['size'] = $props['installed_size'];
            }

            $this->id = 0;
            $this->merge($temp)->save();

            // Store new installs
            if ($this->status == 'install_succeeded') {
                $new_installs[] = $temp;
            }

            // Store uninstalls
            if ($this->status == 'uninstalled') {
                $uninstalls[] = $temp;
            }

        }

        // Store apropriate event:
        if ($new_installs) {
            $count = count($new_installs);
            $msg = array('count' => $count);
            if ($count == 1) {
                $pkg = array_pop($new_installs);
                $msg['pkg'] = $pkg['display_name'] . ' ' . $pkg['version'];
            }
            $this->store_event(
                'success',
                'munki.package_installed',
                json_encode($msg)
            );
        }
        elseif ($uninstalls) {
            $count = count($uninstalls);
            $msg = array('count' => $count);
            if ($count == 1) {
                $pkg = array_pop($uninstalls);
                $msg['pkg'] = $pkg['display_name'] . ' ' . $pkg['version'];
            }
            $this->store_event(
                'success',
                'munki.package_uninstalled',
                json_encode($msg)
            );
        }
    }
}

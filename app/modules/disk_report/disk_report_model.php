<?php

use CFPropertyList\CFPropertyList;

class Disk_report_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'diskreport'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['totalsize'] = 0;
        $this->rs['freespace'] = 0;
        $this->rs['percentage'] = 0;
        $this->rs['smartstatus'] = '';
        $this->rs['volumetype'] = '';
        $this->rs['media_type'] = '';
        $this->rs['busprotocol'] = '';
        $this->rs['internal'] = 0;
        $this->rs['mountpoint'] = '';
        $this->rs['volumename'] = '';
        $this->rs['encrypted'] = 0;
    }
    
    /**
     * Get filevault statistics
     *
     * Get statistics about filevault
     *
     **/
    public function get_filevault_stats($mountpoint = '/')
    {
        $sql = "SELECT COUNT(CASE WHEN encrypted = 1 THEN 1 END) AS encrypted,
						COUNT(CASE WHEN encrypted = 0 THEN 1 END) AS unencrypted
						FROM diskreport
						LEFT JOIN reportdata USING (serial_number)
						WHERE mountpoint = ?
						".get_machine_group_filter('AND');
        return current($this->query($sql, $mountpoint));
    }
    
     /**
     * Get disk type statistics
     * autor tuxudo
     *
     **/
    public function get_disk_type()
    {
        $sql = "SELECT COUNT(CASE WHEN media_type = 'hdd' THEN 1 END) AS hdd,
						COUNT(CASE WHEN media_type = 'ssd' THEN 1 END) AS ssd,
						COUNT(CASE WHEN media_type = 'fusion' THEN 1 END) AS fusion,
						COUNT(CASE WHEN media_type = 'raid' THEN 1 END) AS raid
						FROM diskreport
						LEFT JOIN reportdata USING (serial_number)
						WHERE internal = 1
						".get_machine_group_filter('AND');
        return current($this->query($sql));
    }
    
    /**
     * Get filesystem type statistics
     * autor tuxudo
     *
     **/
    public function get_volume_type()
    {
        $sql = "SELECT COUNT(CASE WHEN volumetype = 'APFS' THEN 1 END) AS apfs,
						COUNT(CASE WHEN volumetype = 'bootcamp' THEN 1 END) AS bootcamp,
						COUNT(CASE WHEN volumetype = 'Journaled HFS+' THEN 1 END) AS hfs
						FROM diskreport
						LEFT JOIN reportdata USING (serial_number)
						WHERE Internal = 1
						".get_machine_group_filter('AND');
        return current($this->query($sql));
    }
    
    /**
     * Get statistics
     *
     * @return array
     * @author
     **/
    public function get_stats($mountpoint = '/', $level1 = 5, $level2 = 10)
    {
        // Convert to GB
        $level1 = $level1 . '000000000';
        $level2 = $level2 . '000000000';
        $level2_minus_one = $level2 - 1;
        $sql = "SELECT COUNT(CASE WHEN freespace > $level2_minus_one THEN 1 END) AS success,
						COUNT(CASE WHEN freespace < $level2 THEN 1 END) AS warning,
						COUNT(CASE WHEN freespace < $level1 THEN 1 END) AS danger
						FROM diskreport
						LEFT JOIN reportdata USING (serial_number)
						WHERE mountpoint = '$mountpoint'
						".get_machine_group_filter('AND');
        return current($this->query($sql));
    }
    
    /**
     * Get SMART Status statistics
     *
     *
     **/
    public function getSmartStats()
    {
        $sql = "SELECT COUNT(CASE WHEN smartstatus='Failing' THEN 1 END) AS failing,
						COUNT(CASE WHEN smartstatus='Verified' THEN 1 END) AS verified,
						COUNT(CASE WHEN smartstatus='Not Supported' THEN 1 END) AS unsupported
						FROM diskreport
						LEFT JOIN reportdata USING(serial_number)
						".get_machine_group_filter();
        return current($this->query($sql));
    }

    // ------------------------------------------------------------------------

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author abn290
     **/
    public function process($plist)
    {

        $parser = new CFPropertyList();
        $parser->parse($plist, CFPropertyList::FORMAT_XML);
        $mylist = $parser->toArray();
        if (! $mylist) {
            throw new Exception("No Disks in report", 1);
        }

        // Convert old style reports from not migrated clients
        if (isset($mylist['DeviceIdentifier'])) {
            $mylist = array($mylist);
        }

        // Delete previous set
        $this->deleteWhere('serial_number=?', $this->serial_number);

        // Copy default values
        $empty = $this->rs;

        foreach ($mylist as $disk) {
            // Reset values
            $this->rs = $empty;
            
            $disk = array_change_key_case($disk, CASE_LOWER);

            // Calculate percentage
            if (isset($disk['totalsize']) && isset($disk['freespace'])) {
                $disk['percentage'] = round(($disk['totalsize'] - $disk['freespace']) /
                    max($disk['totalsize'], 1) * 100);
            }

            $disk['volumetype'] = "-";
            $disk['media_type'] = "hdd";
            if (isset($disk['solidstate']) && $disk['solidstate'] == true) {
                $disk['media_type'] = "ssd";
            }
            if (isset($disk['corestoragecompositedisk']) && $disk['corestoragecompositedisk'] == true) {
                $disk['media_type'] = "fusion";
            }
            if (isset($disk['raidmaster']) && $disk['raidmaster'] == true) {
                $disk['media_type'] = "raid";
            }
            if (isset($disk['filesystemname'])) {
                $disk['volumetype'] = $disk['filesystemname'];
            }
            if (isset($disk['content']) && $disk['content'] == 'Microsoft Basic Data') {
                $disk['volumetype'] = "bootcamp";
            }
            # Legacy FV info field
            if(isset($disk['corestorageencrypted'])) {
                $this->encrypted = $disk['corestorageencrypted'];
            }
            if(isset($disk['fusion']) && $disk['fusion'] == true) {
                $disk['volumetype'] = "apfs_fusion";
            }

            $this->merge($disk);

            // Typecast Boolean values
            $this->internal = (int) $this->internal;
            $this->encrypted = (int) $this->encrypted;

            $this->id = '';
            $this->create();
            
            // Fire event when systemdisk hits a threshold
            if ($this->mountpoint == '/') {
                $type = 'success';
                $msg = '';
                $data = '';
                $lowvalue = 1000; // Lowest value (GB)
                
                // Check SMART Status
                if ($this->smartstatus=='Failing') {
                    $type = 'danger';
                    $msg = 'smartstatus_failing';
                }
                foreach (conf('disk_thresholds', array()) as $name => $value) {
                    if ($this->freespace < $value * 1000000000) {
                        if ($value < $lowvalue) {
                            $type = $name;
                            $msg = 'free_disk_space_less_than';
                            $data = json_encode(array('gb'=> $value));
                            // Store lowest value
                            $lowvalue = $value;
                        }
                    }
                }
                
                if ($type == 'success') {
                    $this->delete_event();
                } else {
                    $this->store_event($type, $msg, $data);
                }
            }
        }
    }
}

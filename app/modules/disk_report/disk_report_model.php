<?php
class Disk_report_model extends Model
{

    function __construct($serial = '')
    {
        parent::__construct('id', 'diskreport'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['TotalSize'] = 0;
        $this->rt['TotalSize'] = 'BIGINT';
        $this->rs['FreeSpace'] = 0;
        $this->rt['FreeSpace'] = 'BIGINT';
        $this->rs['Percentage'] = 0;
        $this->rs['SMARTStatus'] = '';
        $this->rs['VolumeType'] = '';
        $this->rs['BusProtocol'] = '';
        $this->rs['Internal'] = 0; // Boolean
        $this->rs['MountPoint'] = '';
        $this->rs['VolumeName'] = '';
        $this->rs['CoreStorageEncrypted'] = 0; //Boolean
        $this->rs['timestamp'] = 0;

        $this->idx[] = array('serial_number');
        $this->idx[] = array('VolumeType');
        $this->idx[] = array('MountPoint');
        $this->idx[] = array('VolumeName');

        // Schema version, increment when creating a db migration
        $this->schema_version = 2;

        // Create table if it does not exist
        $this->create_table();

    }
    
    /**
     * Get filevault statistics
     *
     * Get statistics about filevault
     *
     **/
    public function get_filevault_stats($mountpoint = '/')
    {
        $sql = "SELECT COUNT(CASE WHEN CoreStorageEncrypted = 1 THEN 1 END) AS encrypted,
						COUNT(CASE WHEN CoreStorageEncrypted = 0 THEN 1 END) AS unencrypted
						FROM diskreport
						LEFT JOIN reportdata USING (serial_number)
						WHERE MountPoint = ?
						".get_machine_group_filter('AND');
        return current($this->query($sql, $mountpoint));
    }

    /**
     * Get statistics
     *
     * @return array
     * @author
     **/
    function get_stats($mountpoint = '/', $level1 = 5, $level2 = 10)
    {
        // Convert to GB
        $level1 = $level1 . '000000000';
        $level2 = $level2 . '000000000';
        $level2_minus_one = $level2 - 1;
        $sql = "SELECT COUNT(CASE WHEN FreeSpace > $level2_minus_one THEN 1 END) AS success,
						COUNT(CASE WHEN FreeSpace < $level2 THEN 1 END) AS warning,
						COUNT(CASE WHEN FreeSpace < $level1 THEN 1 END) AS danger
						FROM diskreport
						LEFT JOIN reportdata USING (serial_number)
						WHERE MountPoint = '$mountpoint'
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
        $sql = "SELECT COUNT(CASE WHEN SMARTStatus='Failing' THEN 1 END) AS failing,
						COUNT(CASE WHEN SMARTStatus='Verified' THEN 1 END) AS verified,
						COUNT(CASE WHEN SMARTStatus='Not Supported' THEN 1 END) AS unsupported
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
    function process($plist)
    {

        require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
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

            // Calculate percentage
            if (isset($disk['TotalSize']) && isset($disk['FreeSpace'])) {
                $disk['Percentage'] = round(($disk['TotalSize'] - $disk['FreeSpace']) /
                    max($disk['TotalSize'], 1) * 100);
            }

            // Determine VolumeType
            $disk['VolumeType'] = "hdd";
            if (isset($disk['SolidState']) && $disk['SolidState'] == true) {
                $disk['VolumeType'] = "ssd";
            }
            if (isset($disk['CoreStorageCompositeDisk']) && $disk['CoreStorageCompositeDisk'] == true) {
                $disk['VolumeType'] = "fusion";
            }
            if (isset($disk['RAIDMaster']) && $disk['RAIDMaster'] == true) {
                $disk['VolumeType'] = "raid";
            }
            if (isset($disk['Content']) && $disk['Content'] == 'Microsoft Basic Data') {
                $disk['VolumeType'] = "bootcamp";
            }

            $this->merge($disk);

            // Typecast Boolean values
            $this->Internal = (int) $this->Internal;
            $this->CoreStorageEncrypted = (int) $this->CoreStorageEncrypted;

            $this->id = '';
            $this->timestamp = time();
            $this->create();
            
            // Fire event when systemdisk hits a threshold
            if ($this->MountPoint == '/') {
                $type = 'success';
                $msg = '';
                $data = '';
                $lowvalue = 1000; // Lowest value (GB)
                
                // Check SMART Status
                if ($this->SMARTStatus=='Failing') {
                    $type = 'danger';
                    $msg = 'smartstatus_failing';
                }
                foreach (conf('disk_thresholds', array()) as $name => $value) {
                    if ($this->FreeSpace < $value * 1000000000) {
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

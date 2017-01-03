<?php
class mbbr_status_model extends Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'mbbr_status'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
        $this->rs['Entitlement status'] = '';
        $this->rs['Machine ID'] = '';
        $this->rs['Installation token'] = '';
        // Schema version, increment when creating a db migration
        $this->schema_version = 0;
        // Add indexes
        $this->idx[] = array('Entitlement status');
        $this->idx[] = array('Machine ID');
        $this->idx[] = array('Installation token');

        // Create table if it does not exist
        $this->create_table();

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial = $serial;
    }
    public function process($data)
    {
        require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
        $parser = new CFPropertyList();
        $parser->parse($data);

        $plist = $parser->toArray();
        foreach (array('Entitlement status', 'Machine ID', 'Installation token' ) as $item) {
            if (isset($plist[$item])) {
                $this->$item = $plist[$item];
            } else {
                $this->$item = '';
            }
        }
        $this->save();
    }
}

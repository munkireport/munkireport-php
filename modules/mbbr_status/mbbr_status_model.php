<?php

use CFPropertyList\CFPropertyList;

class mbbr_status_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'mbbr_status'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rs['entitlement_status'] = '';
        $this->rs['machine_id'] = '';
        $this->rs['install_token'] = '';

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial = $serial;
    }
    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data, CFPropertyList::FORMAT_XML);

        $translate = array(
          'Entitlement status' => 'entitlement_status',
          'Machine ID' => 'machine_id',
          'Installation token' => 'install_token'
        );

        $plist = $parser->toArray();
        foreach ($translate as $search => $item) {
            if (isset($plist[$search])) {
                $this->$item = $plist[$search];
            } else {
                $this->$item = '';
            }
        }
        $this->save();
    }
}

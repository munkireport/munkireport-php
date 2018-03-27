<?php

use CFPropertyList\CFPropertyList;

class munkiinfo_model extends \Model
{

    public function __construct($serial = '')
    {
          parent::__construct('id', 'munkiinfo'); //primary key, tablename
          $this->rs['id'] = 0;
          $this->rs['serial_number'] = $serial;
          $this->rs['munkiinfo_key'] = '';
          $this->rs['munkiinfo_value'] = '';

        if ($serial) {
            $this->retrieve_record($serial);
          
            $this->serial = $serial;
        }
    }

  /**
   * Process data sent by postflight
   *
   * @param string data
   * @author erikng
   **/
    public function process($plist)
    {
        $parser = new CFPropertyList();
        $parser->parse($plist);

        $plist = $parser->toArray();

        $this->deleteWhere('serial_number=?', $this->serial_number);
        $item = array_pop($plist);
        reset($item);
        foreach($item as $key => $val) {
                $this->munkiinfo_key = $key;
                $this->munkiinfo_value = $val;

                $this->id = '';
                $this->save();
        }
    }
}

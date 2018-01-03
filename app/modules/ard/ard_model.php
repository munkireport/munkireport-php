<?php

use CFPropertyList\CFPropertyList;

class Ard_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'ard'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rs['Text1'] = '';
        $this->rs['Text2'] = '';
        $this->rs['Text3'] = '';
        $this->rs['Text4'] = '';
        
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial_number = $serial;
    }

    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data);
        
        $plist = $parser->toArray();

        foreach (array('Text1', 'Text2', 'Text3', 'Text4') as $item) {
            if (isset($plist[$item])) {
                $this->$item = $plist[$item];
            } else {
                $this->$item = '';
            }
        }

        $this->save();
    }
}

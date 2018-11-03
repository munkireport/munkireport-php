<?php

use CFPropertyList\CFPropertyList;

class Ard_model extends \Model
{

    public function __construct($serial = '')
    {
        parent::__construct('id', 'ard'); //primary key, tablename
        $this->rs['id'] = 0;
        $this->rs['serial_number'] = $serial;
        $this->rs['text1'] = '';
        $this->rs['text2'] = '';
        $this->rs['text3'] = '';
        $this->rs['text4'] = '';
        
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial_number = $serial;
    }

    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data);
        $plist = array_change_key_case($parser->toArray(), CASE_LOWER);

        foreach (array('text1', 'text2', 'text3', 'text4') as $item) {
            if (isset($plist[$item])) {
                $this->$item = $plist[$item];
            } else {
                $this->$item = '';
            }
        }

        $this->save();
    }
}

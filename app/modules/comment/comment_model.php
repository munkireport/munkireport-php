<?php
class Comment_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'comment'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['section'] = ''; // Section of the comment
        $this->rs['user'] = ''; // username
        $this->rs['text'] = '';
        $this->rs['html'] = '';
        $this->rs['timestamp'] = 0; // Timestamp
    }
}

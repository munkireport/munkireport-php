<?php

namespace munkireport\models;

class Module_marketplace_model extends \Model
{
    public function __construct($module = '')
    {
        parent::__construct('id', 'modules'); // Primary key, tablename
        $this->rs['id'] = '';
        $this->rs['module'] = '';
        $this->rs['version'] = null;
        $this->rs['url'] = null;
        $this->rs['maintainer'] = null;
        $this->rs['date_updated'] = null;
        $this->rs['core'] = null;
        $this->rs['packagist'] = null;
    }
}

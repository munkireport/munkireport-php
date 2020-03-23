<?php

namespace munkireport\processors;
/**
 * Propertylist processor
 */
class Processor
{
    protected $serial_number;
    protected $module;
    
    function __construct($module, $serial_number)
    {
        $this->module = $module;
        $this->serial_number = $serial_number;
    }
    
    /**
     * Store event
     *
     * Store event for this processor, assumes we have a serial_number
     *
     * @param string $type Use one of 'danger', 'warning', 'info' or 'success'
     * @param string $msg The message
     **/
    public function store_event($type, $msg, $data = '')
    {
        store_event($this->serial_number, $this->module, $type, $msg, $data);
    }
    
    /**
     * Delete event
     *
     * Delete event for this model, assumes we have a serial_number
     *
     **/
    public function delete_event()
    {
        delete_event($this->serial_number, $this->module);
    }

}

<?php 

/**
 * MODULE class
 *
 * @package munkireport
 * @author 
 **/
class MODULE_controller extends Module_controller
{
	
    /*** Protect methods with auth! ****/
    function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }
	
    /**
     * Get MODULE information for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial_number = '')
    {

        if (! $this->authorized()) {
            view('json', array('msg' => 'Not authorized'));
        }
        $columns = [
            'item1',
            'item2',
        ];

        $out = MODULE_model::select($columns)
            ->whereSerialNumber($serial_number)
            ->filter()
            ->limit(1)
            ->first()
            ->toArray();

        view('json', array('msg' => $out));
    }

    public function get_list()
    {
        $out = MODULE_model::selectRaw('item1, count(*) AS count')
            ->filter()
            ->groupBy('item1')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();

        view('json', array('msg' => $out));
}

} // END class MODULE_controller
<?php
class Gsx_model extends \Model
{
    
    protected $error = '';
    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'gsx'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['warrantystatus'] = '';
        $this->rs['coverageenddate'] = '';
        $this->rs['coveragestartdate'] = '';
        $this->rs['daysremaining'] = 0;
        $this->rs['estimatedpurchasedate'] = '';
        $this->rs['purchasecountry'] = '';
        $this->rs['registrationdate'] = '';
        $this->rs['productdescription'] = '';
        $this->rs['configdescription'] = '';
        $this->rs['contractcoverageenddate'] = '';
        $this->rs['contractcoveragestartdate'] = '';
        $this->rs['contracttype'] = '';
        $this->rs['laborcovered'] = '';
        $this->rs['partcovered'] = '';
        $this->rs['warrantyreferenceno'] = '';
        $this->rs['isloaner'] = '';
        $this->rs['warrantymod'] = '';
        $this->rs['isvintage'] = '';
        $this->rs['isobsolete'] = '';

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial_number = $serial;
    }
    
    /**
     * Get GSX statistics
     *
     **/
    public function get_stats($alert = false)
    {
        $out = array();
        $filter = get_machine_group_filter();
        $datefilter = '';
        
        // Check if we have to only return machines due in 30 days
        if ($alert) {
            $thirtydays = date('Y-m-d', strtotime('+30days'));
            $yesterday = date('Y-m-d', strtotime('-1day'));
            $datefilter = "AND (end_date BETWEEN '$yesterday' AND '$thirtydays')";
        }
        $sql = "SELECT count(*) AS count, status
					FROM gsx
					LEFT JOIN reportdata USING (serial_number)
					$filter
					$datefilter
					GROUP BY status
					ORDER BY count DESC";
        
        foreach ($this->query($sql) as $obj) {
            $out[] = $obj;
        }
        
        return $out;
    }

    /**
     * Get GSX supported statistics for widget
     *
     **/
    public function getGSXSupportStats()
    {
        $sql = "SELECT COUNT(CASE WHEN isobsolete = 'Yes' THEN 1 END) AS obsolete,
				COUNT(CASE WHEN isvintage = 'Yes' THEN 1 END) AS vintage,
				COUNT(CASE WHEN isvintage = 'No' AND isobsolete = 'No' AND warrantystatus IS NOT NULL THEN 1 END) AS supported,
				COUNT(CASE WHEN isvintage IS NULL AND isobsolete IS NULL THEN 1 END) AS unknown
				FROM gsx
				LEFT JOIN reportdata USING(serial_number)
				".get_machine_group_filter();
        return current($this->query($sql));
    }

    // ------------------------------------------------------------------------


    /**
     * Check GSX status and update
     *
     * @return void
     * @author John Eberle
     **/
    //function get_gsx_stats($force = FALSE)
    public function run_gsx_stats()
    {
        // Check if we should enable GSX lookup
        // Useful for stopping lookups if IP address changes
        if (conf('gsx_enable')) {
            // Load gsx helper
                require_once(conf('application_path').'helpers/gsx_helper.php');
            
                get_gsx_stats($this);
                // ^^ Comment and uncomment to turn off and on
        }
        
        return $this;
    }

    /**
     * Process method, is called by the client
     *
     * @return void
     * @author John Eberle
     **/
    public function process()
    {
        $this->run_gsx_stats();
    }
}

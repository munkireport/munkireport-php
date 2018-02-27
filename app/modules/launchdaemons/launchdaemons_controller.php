<?php
/**
 * launchdaemons module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Launchdaemons_controller extends Module_controller
{
    
    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }
    
    /**
    * Default method
    *
    * @author AvB
    **/
    public function index()
    {
        echo "You've loaded the launchdaemons module!";
    }
    
    /**

    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_tab_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $sql = "SELECT label, path, disabled, username, groupname, program, programarguments, rootdirectory, workingdirectory, enableglobbing, enabletransactions, ondemand, runatload, umask, timeout, exittimeout, throttleinterval, initgroups, startonmount, startinterval, standardinpath, standardoutpath, standarderrorpath, debug, waitfordebugger, nice, processtype, abandonprocessgroup, lowpriorityio, lowprioritybackgroundio, enablepressuredexit, launchonlyonce, inetdcompatibility, sessioncreate, legacytimers, limitloadtosessiontype, limitloadtohosts, limitloadfromhosts, limitloadtohardware, watchpaths, queuedirectories, keepalive, networkstate, successfulexit, pathstate, otherjobenabled, environmentvariables, machservices, startcalendarminute, startcalendarhour, startcalendarday, startcalendarweekday, softresourcelimitscore, softresourcelimitscpu, softresourcelimitsdata, softresourcelimitsfilesize, softresourcelimitsmemorylock, softresourcelimitsnumberoffiles, softresourcelimitsnumberofprocesses, softresourcelimitsresidentsetsize, softresourcelimitsstack, hardresourcelimitscore, hardresourcelimitscpu, hardresourcelimitsdata, hardresourcelimitsfilesize, hardresourcelimitsmemorylock, hardresourcelimitsnumberoffiles, hardresourcelimitsnumberofprocesses, hardresourcelimitsresidentsetsize, hardresourcelimitsstack
                        FROM launchdaemons 
                        WHERE serial_number = '$serial_number'";
        
        $queryobj = new Launchdaemons_model();
        $launchdaemons_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $launchdaemons_tab)))); 
    }
} // END class Launchdaemon_controller
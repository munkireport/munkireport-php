<?php

use CFPropertyList\CFPropertyList;

class Launchdaemons_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'launchdaemons'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['label'] = '';
        $this->rs['path'] = '';
        $this->rs['disabled'] = 0;
        $this->rs['username'] = '';
        $this->rs['groupname'] = '';
        $this->rs['program'] = '';
        $this->rs['programarguments'] = '';
        $this->rs['rootdirectory'] = '';
        $this->rs['workingdirectory'] = '';
        $this->rs['enableglobbing'] = 0;
        $this->rs['enabletransactions'] = 0;
        $this->rs['ondemand'] = 0;
        $this->rs['runatload'] = 0;
        $this->rs['umask'] = 0;
        $this->rs['timeout'] = 0;
        $this->rs['exittimeout'] = 0;
        $this->rs['throttleinterval'] = 0;
        $this->rs['initgroups'] = 0;
        $this->rs['startonmount'] = 0;
        $this->rs['startinterval'] = 0;
        $this->rs['standardinpath'] = '';
        $this->rs['standardoutpath'] = '';
        $this->rs['standarderrorpath'] = '';
        $this->rs['debug'] = 0;
        $this->rs['waitfordebugger'] = 0;
        $this->rs['nice'] = 0;
        $this->rs['processtype'] = '';
        $this->rs['abandonprocessgroup'] = 0;
        $this->rs['lowpriorityio'] = 0;
        $this->rs['lowprioritybackgroundio'] = 0;
        $this->rs['enablepressuredexit'] = 0;
        $this->rs['launchonlyonce'] = 0;
        $this->rs['inetdcompatibility'] = 0;
        $this->rs['sessioncreate'] = 0;
        $this->rs['legacytimers'] = 0;
        $this->rs['limitloadtosessiontype'] = '';
        $this->rs['limitloadtohosts'] = '';
        $this->rs['limitloadfromhosts'] = '';
        $this->rs['limitloadtohardware'] = '';
        $this->rs['watchpaths'] = '';
        $this->rs['queuedirectories'] = '';
        $this->rs['keepalive'] = 0;
        $this->rs['networkstate'] = 0;
        $this->rs['successfulexit'] = 0;
        $this->rs['pathstate'] = '';
        $this->rs['otherjobenabled'] = '';
        $this->rs['environmentvariables'] = '';
        $this->rs['machservices'] = '';
        $this->rs['startcalendarminute'] = 0;
        $this->rs['startcalendarhour'] = 0;
        $this->rs['startcalendarday'] = 0;
        $this->rs['startcalendarweekday'] = 0;
        $this->rs['startcalendarmonth'] = 0;
        $this->rs['softresourcelimitscore'] = 0;
        $this->rs['softresourcelimitscpu'] = 0;
        $this->rs['softresourcelimitsdata'] = 0;
        $this->rs['softresourcelimitsfilesize'] = 0;
        $this->rs['softresourcelimitsmemorylock'] = 0;
        $this->rs['softresourcelimitsnumberoffiles'] = 0;
        $this->rs['softresourcelimitsnumberofprocesses'] = 0;
        $this->rs['softresourcelimitsresidentsetsize'] = 0;
        $this->rs['softresourcelimitsstack'] = 0;
        $this->rs['hardresourcelimitscore'] = 0;
        $this->rs['hardresourcelimitscpu'] = 0;
        $this->rs['hardresourcelimitsdata'] = 0;
        $this->rs['hardresourcelimitsfilesize'] = 0;
        $this->rs['hardresourcelimitsmemorylock'] = 0;
        $this->rs['hardresourcelimitsnumberoffiles'] = 0;
        $this->rs['hardresourcelimitsnumberofprocesses'] = 0;
        $this->rs['hardresourcelimitsresidentsetsize'] = 0;
        $this->rs['hardresourcelimitsstack'] = 0;
        
        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial = $serial;
    }


    // ------------------------------------------------------------------------
    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {
        // If data is empty, echo out error
        if (! $data) {
            echo ("Error Processing launchdaemons module: No data found");
        } else { 
            
            // Delete previous entries
            $this->deleteWhere('serial_number=?', $this->serial_number);

            // Process incoming launchdaemons.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();

            // Translate strings to db fields
            $translate = array(
                'label' => 'label',
                'path' => 'path',
                'disabled' => 'disabled',
                'username' => 'username',
                'groupname' => 'groupname',
                'program' => 'program',
                'programarguments' => 'programarguments',
                'rootdirectory' => 'rootdirectory',
                'workingdirectory' => 'workingdirectory',
                'enableglobbing' => 'enableglobbing',
                'enabletransactions' => 'enabletransactions',
                'ondemand' => 'ondemand',
                'runatload' => 'runatload',
                'umask' => 'umask',
                'timeout' => 'timeout',
                'exittimeout' => 'exittimeout',
                'throttleinterval' => 'throttleinterval',
                'initgroups' => 'initgroups',
                'startonmount' => 'startonmount',
                'startinterval' => 'startinterval',
                'standardinpath' => 'standardinpath',
                'standardoutpath' => 'standardoutpath',
                'standarderrorpath' => 'standarderrorpath',
                'debug' => 'debug',
                'waitfordebugger' => 'waitfordebugger',
                'nice' => 'nice',
                'processtype' => 'processtype',
                'abandonprocessgroup' => 'abandonprocessgroup',
                'lowpriorityio' => 'lowpriorityio',
                'lowprioritybackgroundio' => 'lowprioritybackgroundio',
                'enablepressuredexit' => 'enablepressuredexit',
                'launchonlyonce' => 'launchonlyonce',
                'inetdcompatibility' => 'inetdcompatibility',
                'sessioncreate' => 'sessioncreate',
                'legacytimers' => 'legacytimers',
                'limitloadtosessiontype' => 'limitloadtosessiontype',
                'limitloadtohosts' => 'limitloadtohosts',
                'limitloadfromhosts' => 'limitloadfromhosts',
                'limitloadtohardware' => 'limitloadtohardware',
                'watchpaths' => 'watchpaths',
                'queuedirectories' => 'queuedirectories',
                'keepalive' => 'keepalive',
                'networkstate' => 'networkstate',
                'successfulexit' => 'successfulexit',
                'pathstate' => 'pathstate',
                'otherjobenabled' => 'otherjobenabled',
                'environmentvariables' => 'environmentvariables',
                'machservices' => 'machservices',
                'startcalendarminute' => 'startcalendarminute',
                'startcalendarhour' => 'startcalendarhour',
                'startcalendarday' => 'startcalendarday',
                'startcalendarweekday' => 'startcalendarweekday',
                'startcalendarmonth' => 'startcalendarmonth',
                'softresourcelimitscore' => 'softresourcelimitscore',
                'softresourcelimitscpu' => 'softresourcelimitscpu',
                'softresourcelimitsdata' => 'softresourcelimitsdata',
                'softresourcelimitsfilesize' => 'softresourcelimitsfilesize',
                'softresourcelimitsmemorylock' => 'softresourcelimitsmemorylock',
                'softresourcelimitsnumberoffiles' => 'softresourcelimitsnumberoffiles',
                'softresourcelimitsnumberofprocesses' => 'softresourcelimitsnumberofprocesses',
                'softresourcelimitsresidentsetsize' => 'softresourcelimitsresidentsetsize',
                'softresourcelimitsstack' => 'softresourcelimitsstack',
                'hardresourcelimitscore' => 'hardresourcelimitscore',
                'hardresourcelimitscpu' => 'hardresourcelimitscpu',
                'hardresourcelimitsdata' => 'hardresourcelimitsdata',
                'hardresourcelimitsfilesize' => 'hardresourcelimitsfilesize',
                'hardresourcelimitsmemorylock' => 'hardresourcelimitsmemorylock',
                'hardresourcelimitsnumberoffiles' => 'hardresourcelimitsnumberoffiles',
                'hardresourcelimitsnumberofprocesses' => 'hardresourcelimitsnumberofprocesses',
                'hardresourcelimitsresidentsetsize' => 'hardresourcelimitsresidentsetsize',
                'hardresourcelimitsstack' => 'hardresourcelimitsstack'
            );

            // Traverse the xml with translations
            foreach ($plist as $daemon) {
                foreach ($translate as $search => $field) {
                    // If key does not exist in $plist, null it
                    if ( ! array_key_exists($search, $daemon) || $daemon[$search] == '') {
                        $this->$field = null;

                    // Set the db fields to be the same as those in the daemon
                    } else {
                        $this->$field = $daemon[$search];
                    }
                }
                // Save the lunch demon
                $this->id = '';
                $this->save();
            }
        }
    }
}

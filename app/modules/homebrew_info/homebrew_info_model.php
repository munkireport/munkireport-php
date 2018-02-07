<?php
class Homebrew_info_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'homebrew_info'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial; $this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['core_tap_head'] = '';
		$this->rs['core_tap_origin'] = ''; 
		$this->rs['core_tap_last_commit'] = '';
		$this->rs['head'] = '';
		$this->rs['last_commit'] = '';
		$this->rs['origin'] = '';
		$this->rs['homebrew_bottle_domain'] = '';
		$this->rs['homebrew_cellar'] = '';
		$this->rs['homebrew_prefix'] = '';
		$this->rs['homebrew_repository'] = '';
		$this->rs['homebrew_version'] = '';
		$this->rs['homebrew_ruby'] = '';
		$this->rs['command_line_tools'] = '';
		$this->rs['cpu'] = '';
		$this->rs['git'] = '';
		$this->rs['clang'] = '';
		$this->rs['java'] = '';
		$this->rs['perl'] = '';
		$this->rs['python'] = '';
		$this->rs['ruby'] = '';
		$this->rs['x11'] = '';
		$this->rs['xcode'] = '';
		$this->rs['macos'] = '';
		$this->rs['homebrew_git_config_file'] = '';
		$this->rs['homebrew_noanalytics_this_run'] = '';
		$this->rs['curl'] = '';

		// Schema version, increment when creating a db migration
		$this->schema_version = 1;

		// Add indexes
		$this->idx[] = array('core_tap_head');
		$this->idx[] = array('core_tap_origin');
		$this->idx[] = array('core_tap_last_commit');
		$this->idx[] = array('head');
		$this->idx[] = array('last_commit');
		$this->idx[] = array('origin');
		$this->idx[] = array('homebrew_bottle_domain');
		$this->idx[] = array('homebrew_cellar');
		$this->idx[] = array('homebrew_prefix');
		$this->idx[] = array('homebrew_repository');
		$this->idx[] = array('homebrew_version');
		$this->idx[] = array('homebrew_ruby');
		$this->idx[] = array('command_line_tools');
		$this->idx[] = array('cpu');
		$this->idx[] = array('git');
		$this->idx[] = array('clang');
		$this->idx[] = array('java');
		$this->idx[] = array('perl');
		$this->idx[] = array('python');
		$this->idx[] = array('ruby');
		$this->idx[] = array('x11');
		$this->idx[] = array('xcode');
		$this->idx[] = array('macos');
		$this->idx[] = array('homebrew_git_config_file');
		$this->idx[] = array('homebrew_noanalytics_this_run');
		$this->idx[] = array('curl');
        
		// Create table if it does not exist
		//$this->create_table();
        
        if ($serial) {
            $this->retrieve_record($serial);
        }

		$this->serial_number = $serial;
	}
	
	// ------------------------------------------------------------------------
    
    
	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author tuxudo
	 **/
	function process($json)
	{        
		// Check if data was uploaded
		if ( ! $json ){
			throw new Exception("Error Processing Request: No JSON file found", 1);
		}

		// Process json into object thingy
		$brewinfo = json_decode($json, true);
                
        // Translate brew info strings to db fields
        $translate = array(
            'CLT' => 'command_line_tools',
            'CPU' => 'cpu',
            'Clang' => 'clang',
            'Core tap HEAD' => 'core_tap_head',
            'Core tap ORIGIN' => 'core_tap_origin',
            'Core tap last commit' => 'core_tap_last_commit',
            'Git' => 'git',
            'HEAD' => 'head',
            'HOMEBREW_BOTTLE_DOMAIN' => 'homebrew_bottle_domain',
            'HOMEBREW_CELLAR' => 'homebrew_cellar',
            'HOMEBREW_PREFIX' => 'homebrew_prefix',
            'HOMEBREW_REPOSITORY' => 'homebrew_repository',
            'HOMEBREW_VERSION' => 'homebrew_version',
            'Homebrew Ruby' => 'homebrew_ruby',
            'Java' => 'java',
            'Last commit' => 'last_commit',
            'ORIGIN' => 'origin',
            'Perl' => 'perl',
            'Python' => 'python',
            'Ruby' => 'ruby',
            'X11' => 'x11',
            'Xcode' => 'xcode',
            'HOMEBREW_GIT_CONFIG_FILE' => 'homebrew_git_config_file',
            'HOMEBREW_NO_ANALYTICS_THIS_RUN' => 'homebrew_noanalytics_this_run',
            'Curl' => 'curl',
            'macOS' => 'macos'
        );
        
        // Traverse the brew info with translations
        foreach ($translate as $search => $field) { 

            if (! array_key_exists($search, $brewinfo[0])){
                // Skip keys that may not exist and null the value
                $this->$field = '';
            } else if (! empty($brewinfo[0][$search])) {  
               // If key is not empty, save it to the object
                    $this->$field = $brewinfo[0][$search];
            } else if ($brewinfo[0][$search] == "0"){
                // Set the value to 0 if it's 0                        
                $this->$field = $brewinfo[0][$search];
            } else {  
                // Else, null the value
                $this->$field = '';
            }
        }
        // Save the info
        $this->save();
    }
}
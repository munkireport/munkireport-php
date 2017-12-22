<?php
class Homebrew_model extends \Model {

	function __construct($serial='')
	{
		parent::__construct('id', 'homebrew'); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
		$this->rs['name'] = '';
		$this->rs['full_name'] = '';
		$this->rs['oldname'] = ''
		$this->rs['aliases'] = '';
		$this->rs['desc'] = ''; $this->rt['desc'] = 'TEXT';
		$this->rs['homepage'] = '';
		$this->rs['installed_versions'] = '';
		$this->rs['versions_stable'] = '';
		$this->rs['linked_keg'] = '';  
		$this->rs['dependencies'] = ''; $this->rt['dependencies'] = 'TEXT';
		$this->rs['build_dependencies'] = ''; $this->rt['build_dependencies'] = 'TEXT';
		$this->rs['recommended_dependencies'] = ''; $this->rt['recommended_dependencies'] = 'TEXT';
		$this->rs['runtime_dependencies'] = ''; $this->rt['runtime_dependencies'] = 'TEXT';
		$this->rs['optional_dependencies'] = ''; $this->rt['optional_dependencies'] = 'TEXT';
		$this->rs['requirements'] = ''; $this->rt['requirements'] = 'TEXT';
		$this->rs['options'] = ''; $this->rt['options'] = 'TEXT';
		$this->rs['used_options'] = ''; $this->rt['used_options'] = 'TEXT';
		$this->rs['caveats'] = ''; $this->rt['caveats'] = 'TEXT';
		$this->rs['conflicts_with'] = '';
		$this->rs['built_as_bottle'] = 0; //TF
		$this->rs['installed_as_dependency'] = 0; //TF
		$this->rs['installed_on_request'] = 0; //TF
		$this->rs['poured_from_bottle'] = 0; //TF
		$this->rs['versions_bottle'] = 0; //TF
		$this->rs['keg_only'] = 0; //TF
		$this->rs['outdated'] = 0; //TF
		$this->rs['pinned'] = 0; //TF
		$this->rs['versions_devel'] = 0; //TF
		$this->rs['versions_head'] = 0; //TF

		// Schema version, increment when creating a db migration
		$this->schema_version = 0;

		// Add indexes
		$this->idx[] = array('built_as_bottle');
		$this->idx[] = array('installed_as_dependency');
		$this->idx[] = array('installed_on_request');
		$this->idx[] = array('poured_from_bottle');
		$this->idx[] = array('keg_only');
		$this->idx[] = array('outdated');
		$this->idx[] = array('pinned');
		$this->idx[] = array('versions_devel');
		$this->idx[] = array('versions_bottle');
		$this->idx[] = array('versions_head');
        
		// Create table if it does not exist
		//$this->create_table();

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
		
		// Delete previous set        
		$this->deleteWhere('serial_number=?', $this->serial_number);

		// Process json into object thingy
		$brews = json_decode($json, true);
        
        $booleans = array('built_as_bottle','installed_as_dependency','installed_on_request','poured_from_bottle','keg_only','outdated','pinned','versions_bottle','versions_head');
        
        $nestedarrays = array('versions','requirements','options','installed');
        
        foreach ($brews as $singlebrew){
    
            // Traverse the brew
            foreach ($singlebrew as $key => $field) {
                
                // Format booleans before processing
                if (in_array($key, $booleans) && $field == "true") {
                    // Send a 1 to the db
                    $this->$key = '1';
                } else if (in_array($key, $booleans)) {
                    // Send a 0 to the db
                    $this->$key = '0';
                } else if (! empty($field) && ! is_array($field)) { 
                    // If key is not empty, save it to the object
                    $this->$key = $field;
                } else if (is_array($field) && ! in_array($key, $nestedarrays) && ! empty($field) && $key != "bottle"){
                    // If is an array and not a nested array, is not empty, and is not the bottle array, condense it to a string and save it
                    $this->$key = implode(", ", $field);
                } else if ($key == "requirements" && ! empty($field)){
                    // Fill out the requirements values from the requirements array
                    $requirements = "";
                    foreach ($field as $requirement){
                        $requirements .= $requirement["name"].", ";
                    }
                    $this->requirements = trim($requirements, ", ");
                } else if ($key == "versions" && ! empty($field)){
                    // Fill out the versions_ values from the versions array
                    $this->versions_stable = $field["stable"];
                    // versions_devel is a 0/1 for false/true
                    if ($field["devel"] != ""){
                        $this->versions_devel = '1';
                    } else{
                        $this->versions_devel = '0';
                    }
                    // versions_bottle is a 0/1 for false/true
                    if ($field["head"] == "bottle"){
                        $this->versions_bottle = '1';
                    } else{
                        $this->versions_bottle = '0';
                    }
                    // versions_head is a 0/1 for false/true
                    if ($field["head"] == "HEAD"){
                        $this->versions_head = '1';
                    } else{
                        $this->versions_head = '0';
                    }
                } else if ($key == "installed" && ! empty($field)){
                    // installed_versions
                    $installed_versions = "";
                    foreach ($field as $installed_version){
                        $installed_versions .= $installed_version["version"].", ";
                    }
                    $this->installed_versions = trim($installed_versions, ", ");
                    
                    $newestinstall = array_pop($field);
                    
                    // check if options array is not blank, then fill from installed
                    if (! empty($newestinstall["used_options"])){
                        // If is an array and not a nested array, is not empty, and is not the bottle array, condense it to a string and save it
                        $this->used_options = implode(", ", $newestinstall["used_options"]);
                    } else {
                        $this->used_options = ""; 
                    }
                    // built_as_bottle is a 0/1 for false/true
                    if ($newestinstall["built_as_bottle"] == "true"){
                        $this->built_as_bottle = '1';
                    } else{
                        $this->built_as_bottle = '0';
                    }
                    // installed_as_dependency is a 0/1 for false/true
                    if ($newestinstall["installed_as_dependency"] == "true"){
                        $this->installed_as_dependency = '1';
                    } else{
                        $this->installed_as_dependency = '0';
                    }
                    // installed_on_request is a 0/1 for false/true
                    if ($newestinstall["installed_on_request"] == "true"){
                        $this->installed_on_request = '1';
                    } else{
                        $this->installed_on_request = '0';
                    }
                    // poured_from_bottle is a 0/1 for false/true
                    if ($newestinstall["poured_from_bottle"] == "true"){
                        $this->poured_from_bottle = '1';
                    } else{
                        $this->poured_from_bottle = '0';
                    }                       
                    // runtime_dependencies
                    if (array_key_exists("runtime_dependencies", $newestinstall)) {
                        if ($newestinstall["runtime_dependencies"] != null){
                            $this->runtime_dependencies = implode(", ", array_column($newestinstall["runtime_dependencies"], 'full_name'));
                        } else {
                            $this->runtime_dependencies = "";
                        }
                    } else {
                            $this->runtime_dependencies = "";
                    }
                    
                } else if ($key == "options" && ! empty($field)){
                // options
                $options = "";
                foreach ($field as $option){
                    $options .= $option["option"]." (".$option["description"]."), ";
                }
                $this->options = trim($options, ", ");
                    
                    
                } else if ($field == "0" && ! is_array($field)){
                    // Set the value to 0 if it's 0
                    $this->$key = $field;
                } else {  
                    // Else, null the value
                    $this->$key = '';   
                }
            }
            // Save the bottles
            $this->id = '';
            $this->save();
            }
		}
	}
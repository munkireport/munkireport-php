<?php

/**
 *
 * @param object deploystudio model instance
 * @author tuxudo (John Eberle)
 * Idea provided by @n8felton
 **/
function pull_ds_data(&$Ds_model)
{
	// Error message
	$error = '';
	
    // Fill in DeployStudio server details
    $ds_server = conf('ds_server');
    $ds_username = conf('ds_username'); 
    $ds_password = conf('ds_password'); 

    // Get computer data from DeployStudio
    $url = urlencode($ds_server.'/computers/get/entry?id='.$Ds_model->serial_number);
    $ds_computer_result = get_url($url);
    
    require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
    $parser = new CFPropertyList();
    $parser->parse($ds_computer_result);
		
    $plist = $parser->toArray();

    foreach(array('architecture', 'cn', 'dstudio-auto-disable', 'dstudio-auto-reset-workflow','dstudio-auto-started-workflow','dstudio-bootcamp-windows-computer-name','dstudio-disabled','dstudio-group','dstudio-host-ard-field-1','dstudio-host-ard-field-2','dstudio-host-ard-field-3','dstudio-host-ard-field-4','dstudio-host-ard-ignore-empty-fields','dstudio-host-delete-other-locations','dstudio-host-model-identifier','dstudio-host-new-network-location','dstudio-host-primary-key','dstudio-host-serial-number','dstudio-host-type','dstudio-hostname','dstudio-last-workflow','dstudio-last-workflow-duration','dstudio-last-workflow-execution-date','dstudio-last-workflow-status','dstudio-mac-addr') AS $item)
	{
		if (isset($plist[$item]))
		{
			$this->$item = $plist[$item];
		}
		else
		{
            $this->$item = '';
		}
    }
        
        $this->save();

    
    // Get auto workflow title, if applicable 
    //if (!empty($ds_computer_result->dstudio-auto-started-workflow))
    //{
    //$url = urlencode($ds_server.'/workflows/get/entry?id='.$ds_computer_result->dstudio-auto-started-workflow);
    //$ds_auto_workflow_result = get_url($url);
    //$Ds_model->ds_auto_wf_title = $ds_auto_workflow_result->title;
    //}
    
    // Get last workflow title, if applicable   
    //if (!empty($ds_computer_result->dstudio-last-started-workflow))
    //{
    //$url = urlencode($ds_server.'/workflows/get/entry?id='.$ds_computer_result->dstudio-last-workflow);
    //$ds_last_workflow_result = get_url($url);
    //$Ds_model->ds_last_wf_title = $ds_last_workflow_result->title;
    //}
    
    // Fill in DeployStudio data from result
    //$Ds_model->architecture = $ds_computer_result->architecture;
    //$Ds_model->cn = $ds_computer_result->computer-name;
    //$Ds_model->dstudio-auto-disable = $ds_computer_result->auto-disable;
    //$Ds_model->dstudio-auto-reset-workflow = $ds_computer_result->auto-reset-workflow;
    //$Ds_model->dstudio-auto-started-workflow = $ds_computer_result->auto-started-workflow;
    //$Ds_model->dstudio-bootcamp-windows-computer-name = $ds_computer_result->bootcamp-windows-computer-name;
    //$Ds_model->dstudio-disabled = $ds_computer_result->disabled;
    //$Ds_model->dstudio-group = $ds_computer_result->group;
    //$Ds_model->dstudio-host-ard-field-1 = $ds_computer_result->host-ard-field-1;
    //$Ds_model->dstudio-host-ard-field-2 = $ds_computer_result->host-ard-field-2;
    //$Ds_model->dstudio-host-ard-field-3 = $ds_computer_result->host-ard-field-3;
    //$Ds_model->dstudio-host-ard-field-4 = $ds_computer_result->host-ard-field-4;
    //$Ds_model->dstudio-host-ard-ignore-empty-fields = $ds_computer_result->ard-ignore-empty-fields;
    //$Ds_model->dstudio-host-delete-other-locations = $ds_computer_result->delete-other-locations;
    //$Ds_model->dstudio-host-model-identifier = $ds_computer_result->host-model-identifier;
    //$Ds_model->dstudio-host-new-network-location = $ds_computer_result->new-network-location;
    //$Ds_model->dstudio-host-primary-key = $ds_computer_result->host-primary-key;
    //$Ds_model->dstudio-host-serial-number =  $ds_computer_result->host-serial-number;
    //$Ds_model->dstudio-host-type = $ds_computer_result->host-type;
    //$Ds_model->dstudio-hostname = $ds_computer_result->hostname;
    //$Ds_model->dstudio-last-workflow-duration = $ds_computer_result->last-workflow-duration;
    //$Ds_model->dstudio-last-workflow-execution-date = $ds_computer_result->last-workflow-execution-date; //fix date
    //$Ds_model->dstudio-last-workflow-status = $ds_computer_result->last-workflow-status;
    //$Ds_model->dstudio-mac-addr = $ds_computer_result->mac-address;
    
    // Save the data
    //$Ds_model->save;
    $error = 'DeployStudio data processed';
    return $error;
}


/**
 * Retrieve url
 *
 * @return mixed string if successfull, FALSE if failed
 * @author AvB, modified by tuxudo
 **/
function get_url($url, $options = array())
{
        
	$http = array('header' => '');
    $http['ssl'] = [ 'verify_peer' => false, 'allow_self_signed'=> true ];
    
    // 'ssl' => [ 'verify_peer' => false, 'allow_self_signed'=> true ]
    // http://stackoverflow.com/questions/14279095/allow-self-signed-certificates-for-https-wrapper

	//$http['user_agent'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A';

	//if(isset($options['method']))
	//{
	//	$http['method'] = $options['method'];
	//	if($options['method'] = 'POST')
	//	{
	//		$http['header'] .= "Content-type: application/x-www-form-urlencoded\r\n";
	//	}
	//}

	//if(isset($options['data']))
	//{
	//	$http['content'] = http_build_query($options['data']);
	//	$http['header'] .= "Content-Length: " . strlen($http['content']) . "\r\n";
	//}
	
	// Add optional timeout
	if(conf('request_timeout'))
	{
		$http['timeout'] = conf('request_timeout');
	}

	$context_options = array('http' => $http);

	// Add optional proxy settings
	if(conf('proxy'))
	{
		add_proxy_server($context_options);
	}

	$context = stream_context_create($context_options);
	return @file_get_contents($url, FALSE, $context);

}

/**
 * Add proxy server variables to context_options
 *
 * @return boolean TRUE if succeeded, FALSE if config error
 * @author AvB
 **/
function add_proxy_server(&$context_options)
{
	$proxy = conf('proxy');

	if ( ! isset($proxy['server']))
	{
		return FALSE;
	}

	$proxy['server'] = str_replace('tcp://', '', $proxy['server']);

	// If port is not set, default to 8080
	$proxy['port'] = isset($proxy['port']) ? $proxy['port'] : 8080;

	$context_options['http']['proxy'] = 'tcp://' . $proxy['server'].':'.$proxy['port'];
	$context_options['http']['request_fulluri'] = TRUE;

	// Authenticated proxy
	if(isset($proxy['username']) && isset($proxy['password']))
	{
		// Encode username and password
		$auth = base64_encode($proxy['username'].':'.$proxy['password']);

		if( ! isset($context_options['http']['header']))
		{
			$context_options['http']['header'] = "";
		}

		// Add authentication header
		$context_options['http']['header'] .= "Proxy-Authorization: Basic $auth\r\n";

	}

	return TRUE;

}

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

	$ds_server = conf('ds_server');

	// Get computer data from DeployStudio
	$url = "{$ds_server}/computers/get/entry?id={$Ds_model->serial_number}";
	$ds_computer_result = get_url($url);

	require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
	$plist = new CFPropertyList();
	$plist->parse($ds_computer_result);

	$plist = $plist->toArray();
	$plist = array_values($plist);
	$plist = $plist[0];

	foreach($plist as $key=>$value){
		$Ds_model->$key = $value;
	}

	if(array_key_exists('dstudio-auto-started-workflow',$plist)){
		$workflow_title = get_workflow_title($plist['dstudio-auto-started-workflow']);
		$Ds_model->{'dstudio-auto-started-workflow'} = $workflow_title;
	}

	if(array_key_exists('dstudio-last-started-workflow',$plist)){
		$workflow_title = get_workflow_title($plist['dstudio-last-started-workflow']);
		$Ds_model->{'dstudio-last-started-workflow'} = $workflow_title;
	}

  // Save the data
  $Ds_model->save();
  $error = 'DeployStudio data processed';
  return $error;
}


/**
 * Retrieve url
 *
 * @return mixed string if successfull, FALSE if failed
 * @author n8felton (Nathan Felton)
 **/
function get_url($url)
{
	$ds_username = conf('ds_username');
	$ds_password = conf('ds_password');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, "$ds_username:$ds_password");
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$return = curl_exec($ch);
	return $return;
}

/**
 *
 * @param UUID of DeployStudio workflow
 * @author n8felton (Nathan Felton)
 **/
function get_workflow_title($workflow_id){
	$ds_server = conf('ds_server');
	$url = "{$ds_server}/workflows/get/entry?id={$workflow_id}";
	$ds_auto_workflow_result = get_url($url);
	$workflow = new CFPropertyList();
	$workflow->parse($ds_auto_workflow_result);
	$ds_auto_workflow_result = $workflow->toArray();
	return $ds_auto_workflow_result['title'];
}

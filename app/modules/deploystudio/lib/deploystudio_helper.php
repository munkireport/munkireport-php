<?php

namespace munkireport\module\deploystudio;

use Exception;
use CFPropertyList\CFPropertyList;

class Deploystudio_helper
{
    
    /**
     *
     * @param object deploystudio model instance
     * @author tuxudo (John Eberle)
     * Idea provided by @n8felton
     **/
    public function pull_deploystudio_data(&$deploystudio_model)
    {
        // Error message
        $error = '';
        
        # Trim off any slashes on the right (DeployStudio does not handle double slashes well)
        $deploystudio_server = rtrim(conf('deploystudio_server'), '/');

        // Get computer data from DeployStudio
        $url = "{$deploystudio_server}/computers/get/entry?id={$deploystudio_model->serial_number}";
        $deploystudio_computer_result = $this->get_deploystudio_url($url);

        if(! $deploystudio_computer_result){
            throw new Exception("No data received from deploystudio server", 1);
        }

        $plist = new CFPropertyList();
        $plist->parse($deploystudio_computer_result);

        $plist = $plist->toArray();
        if( ! $plist){
            throw new Exception("Machine not found in deploystudio server", 1);
        }
        
        $plist = array_values($plist);
        $plist = $plist[0];

        foreach ($plist as $key => $value) {
            $safe_key = str_replace('-', '_', $key);
            $deploystudio_model->$safe_key = $value;
        }

        if (array_key_exists('dstudio-auto-started-workflow', $plist)) {
            $workflow_title = $this->get_workflow_title($plist['dstudio-auto-started-workflow']);
            if($workflow_title != NULL){
     		    $deploystudio_model->{'dstudio_auto_started_workflow'} = $workflow_title;
            } 
        }

        if (array_key_exists('dstudio-last-workflow', $plist)) {
            $workflow_title = $this->get_workflow_title($plist['dstudio-last-workflow']);
            if($workflow_title != NULL){
     		    $deploystudio_model->{'dstudio_last_workflow'} = $workflow_title;
            }
        }

      // Save the data
        $deploystudio_model->save();
        $error = 'DeployStudio data processed';
        return $error;
    }


    /**
     * Retrieve url
     *
     * @return mixed string if successfull, FALSE if failed
     * @author n8felton (Nathan Felton)
     **/
    public function get_deploystudio_url($url)
    {
        $deploystudio_username = conf('deploystudio_username');
        $deploystudio_password = conf('deploystudio_password');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$deploystudio_username:$deploystudio_password");
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
    public function get_workflow_title($workflow_id)
    {
        $deploystudio_server = conf('deploystudio_server');
        $url = "{$deploystudio_server}/workflows/get/entry?id={$workflow_id}";
        $deploystudio_auto_workflow_result = $this->get_deploystudio_url($url);
        $workflow = new \CFPropertyList();
        $workflow->parse($deploystudio_auto_workflow_result);
        $deploystudio_auto_workflow_result = $workflow->toArray();
        return $deploystudio_auto_workflow_result['title'];
    }
}

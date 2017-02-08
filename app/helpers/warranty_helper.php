<?php

/**
 * Unfortunately we have to scrape the page as Apple discontinued the json api
 *
 * @param object warranty model instance
 * @author AvB
 **/
function check_warranty_status(&$warranty_model)
{
    // Error message
    $error = '';
    
    // Check if virtual machine
    // We assume vmware serials contain upper and lower chars
    // There are actual macs with a serial starting with VM
    // Todo: make this check more robust/support other VMs
    if (strtoupper($warranty_model->serial_number) != $warranty_model->serial_number) {
        $warranty_model->status = "Virtual Machine";
        
        // Use reg_timestamp as purchase_date
        $report = new Reportdata_model($warranty_model->serial_number);
        $warranty_model->purchase_date = date('Y-m-d', $report->reg_timestamp);
        $warranty_model->end_date = date('Y-m-d', strtotime('+10 year'));
        
        $machine = new Machine_model($warranty_model->serial_number);
        //$machine->img_url = $matches[1]; Todo: get image url for VM
        $machine->machine_desc = 'VMware virtual machine';
        $machine->save();
        return;
    }
    
    // Previous entry
    if ($warranty_model->end_date) {
        if ($warranty_model->end_date < date('Y-m-d')) {
            $warranty_model->status = 'Expired';
        }
    } else // New entry
    {
        // As of 19 oct 2015 warranty information is behind a Captcha
        // so we can't do any automated lookup anymore
        $warranty_model->status = "Can't lookup warranty";
        $warranty_model->purchase_date = estimate_manufactured_date($warranty_model->serial_number);
        
        // Calculate time to expire
        $max_applecare_years = sprintf('+%s year', conf('max_applecare', 3));
        $purchase_time = strtotime($warranty_model->purchase_date);
        $warranty_model->end_date = date('Y-m-d', strtotime($max_applecare_years, $purchase_time));
    }
    
    
    // Get machine model from apple (only when not set or failed)
    $machine = new Machine_model($warranty_model->serial_number);
    $lookup_failed = array('model_lookup_failed', 'unknown_model');
    if (! $machine->machine_desc or in_array($machine->machine_desc, $lookup_failed)) {
        $machine->machine_desc = model_description_lookup($warranty_model->serial_number);
        $machine->save();
    }

    return $error;
}

/**
 * Estimates the week the machine was manfactured based off it's serial
 * number
 * Ported from python warranty scripts by Adam Reed <adam.reed@anu.edu.au>
 *
 * @return string date
 * @author Arjen van Bochoven
 **/
function estimate_manufactured_date($serial)
{
    # See http://www.macrumors.com/2010/04/16/apple-tweaks-serial-number
    #      -format-with-new-macbook-pro/ for details about serial numbers

    if (strlen($serial) == 11) {
        $year = $serial[2];
        $est_year = 2000 + strpos('   3456789012', $year);
        $week = substr($serial, 3, 2);
        return formatted_manufactured_date($est_year, $week);
    } elseif (strlen($serial) == 12) {
        $year_code = 'cdfghjklmnpqrstvwxyz';
        $year = strtolower($serial[3]);
        $est_year = 2010 + intval(strpos($year_code, $year) / 2);
        $est_half = strpos($year_code, $year) % 2;
        $week_code = ' 123456789cdfghjklmnpqrtvwxy';
        $week = strtolower(substr($serial, 4, 1));
        $est_week = strpos($week_code, $week) + ($est_half * 26);
        return formatted_manufactured_date($est_year, $est_week);
    } else {
        return 'unknown';
    }
}

function formatted_manufactured_date($year, $week)
{
    $strtime = sprintf('%sW%02s1', $year, $week);
    return date('Y-m-d', strtotime($strtime));
}

function model_description_lookup($serial)
{
    if (strpos($serial, 'VMWV') === 0) {
        return 'VMware virtual machine';
    }
    
    $url = sprintf('https://km.support.apple.com/kb/index?page=categorydata&serialnumber=%s', $serial);
    $result = get_url($url);

    if ($result === false) {
        return 'model_lookup_failed';
    }
    
    try {
        $categorydata = json_decode($result);
        if(isset($categorydata->name)){
            return $categorydata->name;
        }
        else{
            return 'unknown_model';
        }
    } catch (Exception $e) {
        return 'model_lookup_failed';
    }
    
}

/**
 * Retrieve url
 *
 * @return mixed string if successfull, FALSE if failed
 * @author AvB
 **/
function get_url($url, $options = array())
{
    $http = array('header' => '');

    $http['user_agent'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A';

    if (isset($options['method'])) {
        $http['method'] = $options['method'];
        if ($options['method'] = 'POST') {
            $http['header'] .= "Content-type: application/x-www-form-urlencoded\r\n";
        }
    }

    if (isset($options['data'])) {
        $http['content'] = http_build_query($options['data']);
        $http['header'] .= "Content-Length: " . strlen($http['content']) . "\r\n";
    }
    

    // Add optional timeout
    if (conf('request_timeout')) {
        $http['timeout'] = conf('request_timeout');
    }

    $context_options = array('http' => $http);

    // Add optional proxy settings
    if (conf('proxy')) {
        add_proxy_server($context_options);
    }
    
    // Add optional ssl settings
    if (conf('ssl_options')) {
        $context_options['ssl'] = conf('ssl_options');
    }

    $context = stream_context_create($context_options);
    return @file_get_contents($url, false, $context);
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

    if (! isset($proxy['server'])) {
        return false;
    }

    $proxy['server'] = str_replace('tcp://', '', $proxy['server']);

    // If port is not set, default to 8080
    $proxy['port'] = isset($proxy['port']) ? $proxy['port'] : 8080;

    $context_options['http']['proxy'] = 'tcp://' . $proxy['server'].':'.$proxy['port'];
    $context_options['http']['request_fulluri'] = true;

    // Authenticated proxy
    if (isset($proxy['username']) && isset($proxy['password'])) {
    // Encode username and password
        $auth = base64_encode($proxy['username'].':'.$proxy['password']);

        if (! isset($context_options['http']['header'])) {
            $context_options['http']['header'] = "";
        }

        // Add authentication header
        $context_options['http']['header'] .= "Proxy-Authorization: Basic $auth\r\n";
    }

    return true;
}

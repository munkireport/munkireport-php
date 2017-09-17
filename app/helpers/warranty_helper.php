<?php

use munkireport\lib\Request;

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
    model_description_lookup($warranty_model->serial_number);
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
    
    $options = [
        'query' => [
            'page' => 'categorydata',
            'serialnumber' => $serial
        ]
    ];
    
    $client = new Request();
    $result = $client->get('http://km.support.apple.com/kb/index', $options);

    if ( ! $result) {
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

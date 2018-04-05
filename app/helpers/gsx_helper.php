<?php

/**
 *
 * @param object gsx model instance
 * @author John Eberle (tuxudo)
 **/

// Function run by gsx module
function get_gsx_stats(&$gsx_model)
{
    // Error message
    $error = '';

    // Import gsxlib - https://github.com/filipp/gsxlib
    // Set up variables
    include_once(conf('application_path').'lib/gsxlib/gsxlib.php');
    $_ENV['GSX_CERT'] = conf('gsx_cert');
    $_ENV['GSX_KEYPASS'] = conf('gsx_cert_keypass');
    $sold_to = conf('gsx_sold_to');
    $ship_to = conf('gsx_ship_to');
    $username = conf('gsx_username');
    
    //$conf['gsx_date_format'] = 'd/m/y';
    // Get the current local date format from config
    // Because Apple can't follow ISO standards
    if (! conf('gsx_date_format')) {
        $gsx_date_format = 'm/d/y'; // Default is US format
    } else {
        $gsx_date_format = conf('gsx_date_format');
    }
    
    // If gsx_ship_to is not set, set it to gsx_sold_to
    if (! conf('gsx_ship_to')) {
        $ship_to = $sold_to;
    }
    
    // Pull from gsxlib
    $gsx = GsxLib::getInstance($sold_to, $username);
    try {
        $result = $gsx->warrantyStatus($gsx_model->serial_number, $ship_to);
    } // Catch errors
    catch (Exception $e) {
        // If obsolete, process and run stock warranty lookup
        if ($e->getMessage() === "The serial number entered has been marked as obsolete. If you feel this is in error, please verify and re-enter the serial number.") {
        // Load warranty_helper and run stock warranty functions
            require_once(conf('application_path').'helpers/warranty_helper.php');
            $gsx_model->productdescription = model_description_lookup($gsx_model->serial_number);
            $gsx_model->warrantystatus = 'Obsolete';
            $gsx_model->warrantymod = 'Expired';
            $gsx_model->partcovered = 'No';
            $gsx_model->laborcovered = 'No';
            $gsx_model->daysremaining = '0';
            $gsx_model->isloaner = 'No';
            $gsx_model->isobsolete = 'Yes';
            $gsx_model->isvintage = 'No';
            
            
        // Don't overwrite actual GSX data with guessed data
        // For recently obsoleted machines
            if (is_null($gsx_model->estimatedpurchasedate) || ($gsx_model->estimatedpurchasedate === "")) {
                $gsx_model->estimatedpurchasedate = estimate_manufactured_date($gsx_model->serial_number);
            }
            if (is_null($gsx_model->coveragestartdate) || ($gsx_model->coveragestartdate === "")) {
                $gsx_model->coveragestartdate = $gsx_model->estimatedpurchasedate;
            }
            if (is_null($gsx_model->registrationdate) || ($gsx_model->registrationdate === "")) {
                $gsx_model->registrationdate = $gsx_model->estimatedpurchasedate;
            }
            if (is_null($gsx_model->coverageenddate) || ($gsx_model->coverageenddate === "")) {
                $gsx_model->coverageenddate = date('Y-m-d', strtotime('+1 year', strtotime($gsx_model->estimatedpurchasedate)));
            }

        // Update the stock warranty tables
            $warranty = new Warranty_model($gsx_model->serial_number);
            $warranty->purchase_date = $gsx_model->estimatedpurchasedate;
            $warranty->end_date = $gsx_model->coverageenddate;
            $warranty->status = 'Expired';
            $warranty->save();

        // Update the stock machine tables
            $machine = new Machine_model($gsx_model->serial_number);
            //$machine->img_url = $matches[1]; Todo: get image url for VM
            $machine->machine_desc = $gsx_model->productdescription;
            $machine->save();

            $gsx_model->save();
            $error = 'GSX Lookup failed - machine is Obsolete - running stock warranty lookup';
       
        //check_status();
            return $error;
        } // If error is not obsolete, return error
        else {
            return $e->getMessage();
        }
    }
    
    // Catch GSX lookup fails
    if ($result === false) {
    // Load warranty_helper and run stock warranty functions
        require_once(conf('application_path').'helpers/warranty_helper.php');
        $gsx_model->warrantystatus = 'GSX lookup failed';
        $gsx_model->productdescription = model_description_lookup($gsx_model->serial_number);
        $gsx_model->warrantymod = "Lookup failed";
        
        // Don't overwrite actual GSX data with guessed data
        if (is_null($gsx_model->estimatedpurchasedate) || ($gsx_model->estimatedpurchasedate === "")) {
            $gsx_model->estimatedpurchasedate = estimate_manufactured_date($gsx_model->serial_number);
        }
        if (is_null($gsx_model->coveragestartdate) || ($gsx_model->coveragestartdate === "")) {
            $gsx_model->coveragestartdate = $gsx_model->estimatedpurchasedate;
        }
        if (is_null($gsx_model->registrationdate) || ($gsx_model->registrationdate === "")) {
            $gsx_model->registrationdate = $gsx_model->estimatedpurchasedate;
        }
        if (is_null($gsx_model->coverageenddate) || ($gsx_model->coverageenddate === "")) {
            $gsx_model->coverageenddate = date('Y-m-d', strtotime('+1 year', strtotime($gsx_model->estimatedpurchasedate)));
        }
        
        $gsx_model->save();
        
        $error = 'GSX Lookup failed - running stock warranty lookup';
        return $error;
    } else {
        // Rename warranty status for stock warranty stuff
        $local_warrantyStatus = str_replace(array('AppleCare Protection Plan','Out Of Warranty (No Coverage)','Apple Limited Warranty'), array('Supported','Expired','No Applecare'), $result->warrantyStatus);
    
        // Coverage Status
        if (empty($result->contractType)) {
            $gsx_model->contracttype = "";
        } else {
            $gsx_model->contracttype = str_replace(array('LI','LP','RP','IR','LS','CC','CS','DO','MU','OO','PA','QP','RE','G9','RA','PP','C1','C2','C3','C4','C5','TC','PT','EC','CL','CP','CI','CW','VW','VP','RI','RW'), array('Apple Limited Warranty','Apple Limited Warranty','Repeat Service','Internal Repairs','Lost Shipments (Coverage)','Custom Bid Contracts','Customer Satisfaction (CS) Code','DOA Coverage','Missing Upon 1st Use','Out Of Warranty (No Coverage)','AppleCare Parts Agreement','Quality Program','Repeat Service','Pending Coverage Check','AppleCare Repair Agreement','AppleCare Protection Plan','AppleCare Protection Plan','AppleCare Parts Agreement','AppleCare Repair Agreement','Custom Bid Contracts','Extended Contract','Edu/Govt Warranty (Australia)','Additional Part Coverage','Additional Service Coverage','Consumer Law Coverage','Consumer Law Coverage','Consumer Law Repeat Coverage','Consumer Law Repeat Coverage','Variable Warranty','Variable Warranty','Variable Warranty Repeat','Variable Warranty Repeat'), $result->contractType);
        }
        
        // Fix for non-AppleCare machines
        if (empty($result->contractCoverageEndDate)) {
            $gsx_model->contractcoverageenddate = '';
        } else {
            $gsx_model->contractcoverageenddate = date_format(date_create_from_format($gsx_date_format, $result->contractCoverageEndDate), 'Y-m-d');
        }
        
        if (empty($result->contractCoverageStartDate)) {
            $gsx_model->contractcoveragestartdate = '';
        } else {
            $gsx_model->contractcoveragestartdate = date_format(date_create_from_format($gsx_date_format, $result->contractCoverageStartDate), 'Y-m-d');
        }
        
        if (empty($result->laborCovered)) {
            $gsx_model->laborcovered = 'No';
        } else {
            $gsx_model->laborcovered = str_replace(array('Y','N'), array('Yes','No'), $result->laborCovered);
        }
        
        if (empty($result->partCovered)) {
            $gsx_model->partcovered = 'No';
        } else {
            $gsx_model->partcovered = str_replace(array('Y','N'), array('Yes','No'), $result->partCovered);
        }
        
        // Update the stock machine tables
        $machine = new Machine_model($gsx_model->serial_number);
        //$machine->img_url = $matches[1]; Todo: get image url for VM
        $machine->machine_desc = str_replace(array('~VIN,'), array(''), $result->productDescription);
        $machine->save();
        
        // Translate gsxlib to MunkiReport DB
        $gsx_model->warrantymod = $local_warrantyStatus;
        $gsx_model->warrantystatus = $result->warrantyStatus;
        $gsx_model->daysremaining = $result->daysRemaining;
        $gsx_model->estimatedpurchasedate = date_format(date_create_from_format($gsx_date_format, $result->estimatedPurchaseDate), 'Y-m-d');
        $gsx_model->purchasecountry = $result->purchaseCountry;
        $gsx_model->registrationdate = date_format(date_create_from_format($gsx_date_format, $result->registrationDate), 'Y-m-d');
        $gsx_model->productdescription = str_replace(array('~VIN,'), array(''), $result->productDescription);
        $gsx_model->configdescription = $result->configDescription;
        $gsx_model->isloaner = str_replace(array('Y','N'), array('Yes','No'), $result->isLoaner);
        $gsx_model->isobsolete = 'No';
        
        // Check if Vintage and write flag
        $vintageCheck = substr($gsx_model->configdescription, 0, 3);
        if ($vintageCheck === "VIN") {
            $gsx_model->isvintage = 'Yes';
        } else {
            $gsx_model->isvintage = 'No';
        }
        
        // Fix for non-obsolete and out of warranty machines
        if (empty($result->coverageEndDate)) {
            if ($result->warrantyStatus === "Out Of Warranty (No Coverage)") {
                if (empty($result->contractCoverageEndDate)) {
                    $gsx_model->coverageenddate = date("Y-m-d", strtotime(date_format(date_create_from_format($gsx_date_format, $result->registrationDate), 'Y-m-d') . " + 365 day"));
                } else {
                    $gsx_model->coverageenddate = date_format(date_create_from_format($gsx_date_format, $result->contractCoverageEndDate), 'Y-m-d');
                }
            } else {
                $gsx_model->coverageenddate = date("Y-m-d", strtotime(date_format(date_create_from_format($gsx_date_format, $result->registrationDate), 'Y-m-d') . " + 365 day"));
            }
        } else {
            $gsx_model->coverageenddate = date_format(date_create_from_format($gsx_date_format, $result->coverageEndDate), 'Y-m-d');
        }
        
        if (empty($result->coverageStartDate)) {
            if ($result->warrantyStatus === "Out Of Warranty (No Coverage)") {
                $gsx_model->coveragestartdate = date_format(date_create_from_format($gsx_date_format, $result->registrationDate), 'Y-m-d');
            } else {
                $gsx_model->coveragestartdate = '';
            }
        } else {
            $gsx_model->coveragestartdate = date_format(date_create_from_format($gsx_date_format, $result->coverageStartDate), 'Y-m-d');
        }
        
        // Service Level Agreement
        // Fix for different warranty type returns from GSX
        if (empty($result->warrantyReferenceNo)) {
            $gsx_model->warrantyreferenceno = $result->slaGroupDescription;
        } else {
            $gsx_model->warrantyreferenceno = $result->warrantyReferenceNo;
        }
        
        // Update the stock warranty tables
        $warranty = new Warranty_model($gsx_model->serial_number);
        $warranty->purchase_date = $gsx_model->estimatedpurchasedate;
        $warranty->end_date = $gsx_model->contractcoverageenddate;
        $warranty->status = $local_warrantyStatus;
        $warranty->save();
        
        // Save that stuff :D
        $gsx_model->save();
        $error = 'GSX data processed';
        return $error;
    }
        return $error;
}

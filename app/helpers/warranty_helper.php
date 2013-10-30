<?php

/**
 * Unfortunately we have to scrape the page as Apple discontinued the json api
 *
 * @param object warranty model instance
 * @author AvB
 **/
function check_warranty_status(&$warranty_model)
{
	// Check if virtual machine 
	// We assume vmware serials contain upper and lower chars
	// There are actual macs with a serial starting with VM
	// Todo: make this check more robust/support other VMs
	if(strtoupper($warranty_model->serial_number) != $warranty_model->serial_number)
	{
		$warranty_model->status = "Virtual Machine";
		
		// Use reg_timestamp as purchase_date
		$report = new Reportdata($warranty_model->serial_number);
		$warranty_model->purchase_date = date('Y-m-d', $report->reg_timestamp);
		$warranty_model->end_date = date('Y-m-d', strtotime('+10 year'));
		
		$machine = new Machine($warranty_model->serial_number);
		//$machine->img_url = $matches[1]; Todo: get image url for VM
		$machine->machine_desc = 'VMware virtual machine';
		$machine->save();
		return;
	}
	
	$url = 'https://selfsolve.apple.com/wcResults.do';
	$data = array ('sn' => $warranty_model->serial_number, 'num' => '0');
	$data = http_build_query($data);

	$context_options = array (
	        'http' => array (
	            'method' => 'POST',
	            'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
	                . "Content-Length: " . strlen($data) . "\r\n",
	            'content' => $data
	            )
	        );

	$context = stream_context_create($context_options);
	$result = file_get_contents($url, FALSE, $context);

	if(preg_match('/invalidserialnumber/', $result))
	{
		// Check invalid serial
		$warranty_model->status = 'Invalid serial number';
	}
	elseif(preg_match("/RegisterProduct.do\?productRegister=Y/", $result))
	{
		// Check registration
		$warranty_model->status = 'Unregistered serialnumber';
	}
	elseif(preg_match('/warrantyPage.warrantycheck.displayHWSupportInfo\(false/', $result))
	{
		// Get expired status
		$warranty_model->status = 'Expired';
		//$warranty_model->end_date = '0000-00-00';
	}
	elseif(preg_match('/warrantyPage.warrantycheck.displayHWSupportInfo\(true([^\)])+/', $result, $matches))
	{
		// Get support status

		if(preg_match('/Limited Warranty\./', $matches[0]))
		{
			$warranty_model->status = 'No Applecare';
		}
		else
		{
			$warranty_model->status = 'Supported';
		}
					
		// Get estimated exp date
		if(preg_match('/Estimated Expiration Date: ([^<]+)</', $matches[0], $matches))
		{
			$exp_time = strtotime($matches[1]);
			$warranty_model->end_date = date('Y-m-d', $exp_time);

			if($warranty_model->status == 'Supported')
			{
				$warranty_model->purchase_date = date('Y-m-d', strtotime('-3 years', $exp_time));
			}
			else
			{
				$warranty_model->purchase_date = date('Y-m-d', strtotime('-1 year', $exp_time));
			}
		}	
	}
	else
	{
		$warranty_model->status = 'No information found';
	}

	// No valid purchase date, use the estimated manufacture date
	if( ! $warranty_model->purchase_date OR 
		! preg_match('/\d{4}-\d{2}-\d{2}/', $warranty_model->purchase_date))
	{
		// Get est. manufacture date
		$warranty_model->purchase_date = estimate_manufactured_date($warranty_model->serial_number);
		
	}

	// No expiration date, use the estimated manufacture date + n year
	if( ! $warranty_model->end_date)
	{
		$man_time = strtotime($warranty_model->purchase_date);

		// If we're within 3 years, after man_date we did not have AppleCare
		// So end_date = man_date + 1 year
		if(strtotime('+3 years', $man_time) > time())
		{
			$warranty_model->end_date = date('Y-m-d', strtotime('+1 year', $man_time));
		}
		else // end_date = man_date + 3 yrs (assume we had applecare)
		{
			$warranty_model->end_date = date('Y-m-d', strtotime('+3 years', $man_time));
		}

	}
	
	// Get machine model from apple
	$machine = new Machine($warranty_model->serial_number);
	$machine->machine_desc = model_description_lookup($warranty_model->serial_number);
	$machine->save();

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

	if(strlen($serial) == 11)
	{
		$year = $serial[2];
		$est_year = 2000 + strpos('   3456789012', $year);
		$week = substr($serial, 3, 2);
		return formatted_manufactured_date($est_year, $week);
	}
	elseif(strlen($serial) == 12)
	{
		$year_code = 'cdfghjklmnpqrstvwxyz';
		$year = strtolower($serial[3]);
		$est_year = 2010 + intval(strpos($year_code, $year) / 2);
		$est_half = strpos($year_code, $year) % 2;
		$week_code = ' 123456789cdfghjklmnpqrtvwxy';
		$week = strtolower(substr($serial, 4, 1));
		$est_week = strpos($week_code, $week) + ($est_half * 26);
		return formatted_manufactured_date($est_year, $est_week);
	}
	else
	{
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
	if(strpos($serial, 'VMWV') === 0)
	{
		return 'VMware virtual machine';
	}
	$snippet = substr($serial, 8);
    $url = sprintf('http://support-sp.apple.com/sp/product?cc=%s&lang=en_US', $snippet);
	$result = file_get_contents($url);
	if(preg_match('#<configCode>(.*)</configCode>#', $result, $matches))
	{
		return($matches[1]);
	}

	return 'Unknown model';

}
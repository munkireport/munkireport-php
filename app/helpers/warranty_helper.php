<?php

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
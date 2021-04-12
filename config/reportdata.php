<?php
return [
       
	/*
	|===============================================
	| Client inactivity
	|===============================================
	|
	| Set this to be the number of days until a client shows as
	| being inactive in the round Client Activity widget, default is
	| 30 days.
	|
	*/
	'days_inactive' => env('REPORTDATA_DAYS_INACTIVE', 30),
  'ip_config_path' => env('REPORTDATA_IP_CONFIG_PATH', module_conf('ip_ranges.yml')),
];
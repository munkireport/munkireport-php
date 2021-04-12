<?php 

$this->view('listings/default',
[
	"i18n_title" => 'client.report',
	"js_link" => "module/reportdata/js/reportdata",
	"table" => [
		[
			"column" => "machine.computer_name",
			"i18n_header" => "listing.computername",
			"formatter" => "clientDetail",
			"tab_link" => "summary",
		],
		[
			"column" => "reportdata.serial_number",
			"i18n_header" => "displays_info.machineserial",
		],
		[
			"column" => "reportdata.long_username",
			"i18n_header" => "username",
		],  
		[
			"column" => "machine.os_version",
			"i18n_header" => "os.version",
			"formatter" => "osVersion",
			"filter" => "osFilter",
		],
		["column" => "machine.buildversion", "i18n_header" => "buildversion",],
		["column" => "machine.machine_name", "i18n_header" => "type",],
		["column" => "warranty.status", "i18n_header" => "warranty.status",],
		[
			"column" => "reportdata.uptime",
			"i18n_header" => "uptime",
			"formatter" => "uptimeFormatter",
			"filter" => "uptimeFilter"
		],
		[
			"column" => "reportdata.timestamp",
			"i18n_header" => "listing.checkin",
			"formatter" => "timestampToMoment",
		],
		[
			"column" => "reportdata.reg_timestamp",
			"i18n_header" => "reg_date",
			"formatter" => "timestampToMoment",
		],
		["column" => "munkireport.manifestname", "i18n_header" => "munkireport.manifest.manifest",],
	]
]);

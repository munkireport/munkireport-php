<?php 

$this->view('listings/default',
[
	"i18n_title" => 'MODULE.listing.title',
	"table" => [
		[
			"column" => "machine.computer_name",
			"i18n_header" => "listing.computername",
			"formatter" => "clientDetail",
			"tab_link" => "MODULE-tab",
		],
		[
			"column" => "reportdata.serial_number",
			"i18n_header" => "serial",
		],
		[
			"column" => "MODULE.item1",
			"i18n_header" => "MODULE.listing.item1",
		],
		[
			"column" => "MODULE.item2",
			"i18n_header" => "MODULE.listing.item2",
		],
	]
]);

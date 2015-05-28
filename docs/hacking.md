Hacking instructions for munkireport-php (at the moment just a braindump)

## Views

All views are in the views folder, if you want to modify the views you can override the view path by adding

	$conf['view_path']
  
to config.php and point it to a different location outside the munkireport app directory.

If you just want to change de dashboard, you can add a custom dashboard:

	app/views/dashboard/custom_dashboard.php

## Add more data

To add new data to munkireport2 you can set up a module. A module is a directory that contains 
* an install script for the client (which will gather the appropriate data and point munkireport to it)
* a model (which describes how the data is represented in the database)
* optionally a controller (which you can use to download additional files)

If you want to understand how modules work, take a look at some modules in the modules directory.

## Graphs

Munkireport comes with a bundled graphing library: flotr2.

Munkireport comes with a wrapper function around flotr2 that retrieves graph data and plots a graph. The wrapper is called DrawGraph()

### Network pie graph

If you want to plot where your clients are in the network, you can use the network pie. Global network locations are in config.php.
If you want to use those, just add an empty parameter object (parms = {}). 

			// Override network settings in config.php
			var parms = { 
				"Campus": ["145.108.", "130.37."]
			};

			drawGraph("<?php echo url('module/reportdata/ip'); ?>", '#ip-plot', pieOptions, parms);


## Variables

Don't pollute the $GLOBALS array, at the moment there are a handful of variables passed around via $GLOBALS:

* $GLOBALS['alerts'] - alerts and messages
* $GLOBALS['auth'] - authorization variable (currently only in use for report)
* $GLOBALS['conf'] - config items access with conf()
* $GLOBALS['dbh'] - the database handle
* $GLOBALS['version'] - the current version of MunkiReport

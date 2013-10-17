Hacking instructions for munkireport-php (at the moment just a braindump)

# Munkireport 2

If you want to contribute to munkireport2, please fork the repository and send a pull request for your changes.

## Modules

To add new data to munkireport2 you can set up a module. A module is a directory that contains 
* an install script for the client (which will gather the appropriate data and point munkireport to it)
* a model (which describes how the data is represented in the database)
* optionally a controller (which you can use to download additional files)

If you want to understand how modules work, take a look at the modules directory.

## Graphs

Munkireport comes with a bundled graphing library: flotr2.

There is a wrapper function around flotr2 that retrieves graph data and plots a graph called DrawGraph()

### Network pie graph

If you want to plot where your clients are in the network, you can use the network pie. Global network locations are in config.php.
If you want to use those, just add an empty parameter object (parms = {}). 

			// Override network settings in config.php
			var parms = { 
				"Campus": ["145.108.", "130.37."]
			};

			drawGraph("<?=url('flot/ip')?>", '#ip-plot', pieOptions, parms);




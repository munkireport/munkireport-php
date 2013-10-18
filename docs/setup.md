Setup
=====

First setup the server, the clients use the server to pull down the installation scripts.

On the server
---
 1. Put all files in the root directory of your website (for subdirs,
    see below). Make sure you also copy the .htaccess file.
 2. Create config.php in the root directory of your website. This file overrides the settings in config_default.php
 3. Check if the directory /app/db/ is writeable by the webserver.

Munkireport is able to detect if it is running from a subdirectory. If automatic detection fails, you can also specify the subdirectory in config.php.
So if you want to run munkireport from http://munki.mysite.org/report/
add the following to config.php:

	$conf['subdirectory'] = '/report/';

You're done with the server and should be able to see a webpage with an empty
table when you visit the website with a browser.


Setting up a client manually
---
 1. Open Terminal.app
 2. Type: `sudo /bin/bash -c "$(curl -s http://example.com/munkireport-php/index.php?/install)"`


Setting up clients with munki
---
 1. Download the pkginfo file
		 `curl -s http://example.com/munkireport-php/index.php?/install/plist -o MunkiReport.plist`
 2. Copy MunkiReport.plist into your Munki repository
 3. Run /usr/local/munki/makecatalogs



Running MunkiReport with mod_rewrite
---
If youre running munkireport on an apache webserver and you want to use mod_rewrite (which gives you nicer urls), you'll have
to change the following:

 1. Add `$conf['index_page'] = '';` to config.php


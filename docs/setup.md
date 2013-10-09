Setup
=====

First setup the server, the clients use the server to pull down the installation scripts.

On the server
---
 1. Put all files in the root directory of your website (for subdirs,
    see below). Make sure you also copy the .htaccess file.
 2. Create config.php in the root directory of your website. This file overrides the settings in config_default.php
 3. Check if the directory /app/db/ is writeable by the webserver.

If you want to run munkireport from a subdirectory, change $subdirectory in
config.php, So if want to run munkireport from http://munki.mysite.org/report/
change the following:

config.php - $subdirectory = '/report/';

You're done with the server and should be able to see a webpage with an empty
table when you visit the website with a browser.


SETTING UP CLIENTS MANUALLY
---
 1. Open Terminal.app
 2. Type:
		sudo /bin/bash -c "`http://example.com/munkireport-php/install`"


SETTING UP CLIENTS WITH MUNKI
---
 1. Download the pkginfo file
		- curl http://example.com/munkireport-php/install/plist -o MunkiReport.plist
 2. Copy MunkiReport.plist into your Munki repository
 3. Run /usr/local/munki/makecatalogs



RUNNING MUNKI REPORT WITHOUT MOD_REWRITE
---
If you can't use mod_rewrite, you can run munkireport without it. You'll have
to change the following:
 1. Set $index_page = 'index.php'; to your config file

Setup
=====

First setup the server - the clients use the server to pull down the installation scripts.


On the server
---

 1. Use git to checkout the latest version or download the zip file and put all files in the root directory of your website (for subdirs, see below).
 2. Create config.php in the root directory of your website. Make sure it has at least `<?php` in the top of the file. config.php overrides the settings in config_default.php. To configure, simply copy any settings over from config_default.php to config.php and make the changes there.
 3. Check if the directory /app/db/ is writeable by the webserver (only when using sqlite) 
	* Note that sqlite is the default, but mysql is also supported. check the config_default.php for the proper values if you wish to substitute a mysql database.


Create the first user
---
 
 1. Visit the site with a webbrowser, you'll be prompted to create a user and password
 2. Append the generated hash line to config.php
 3. Now refresh the page in your browser, and you should be able to log in with the credentials you just created.


Advanced server setup
---

### Running MunkiReport from a subdir

Munkireport should able to detect if it is running from a subdirectory. If automatic detection fails, you can also specify the subdirectory in config.php.
So if you want to run munkireport from http://munki.mysite.org/report/
add the following to config.php:

    $conf['subdirectory'] = '/report/';

You're done with the server and should be able to see a webpage with an empty
table when you visit the website with a browser.


#### A simple Apache .vhost config to get you started

    <VirtualHost *:80>
      ServerAdmin webmaster@example.com
      ServerName  munkireport-php.example.com
      ServerAlias munkireport-php.example.com
	  
      AddDefaultCharset utf-8    
      DocumentRoot /srv/munkireport-php
        <Directory />
            Options FollowSymLinks
        </Directory>
      LogLevel warn
      CustomLog /var/log/apache2/munkireport-php.example.com-access.log combined
      ErrorLog /var/log/apache2/munkireport-php.example.com-error.log
    </VirtualHost>


#### Running MunkiReport with mod_rewrite

If you're running munkireport on an apache webserver and you want to use mod_rewrite (which gives you nicer urls), you'll have
to change the following:

 1. Add `$conf['index_page'] = '';` to config.php


Setting up a client manually
---

1. Open Terminal.app
2. Type: `sudo /bin/bash -c "$(curl -s http://example.com/index.php?/install)"`


Setting up clients with munki
---

1. Download the pkginfo file
    `curl -s http://example.com/index.php?/install/plist -o MunkiReport.plist`
2. Copy MunkiReport.plist into your Munki repository (in your pkgsinfo directory)
3. Run makecatalogs, and be sure to add it to a manifest as well.
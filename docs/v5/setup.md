Setup
=====

First setup the server - the clients use the server to pull down the installation scripts.


On the server
---

 1. Use git to checkout the latest version or download the zip file and put all files in the root directory of your website (for subdirs, see below).
 2. Create config.php in the root directory of your website. Make sure this file starts with `<?php` and has no whitespace before that, don't add the closing tag for php. config.php overrides the settings in config_default.php. To configure, simply copy relevant settings over from config_default.php to config.php and make the changes in config.php.
 3. Database setup: when using SQLite as backend (which is the default), check if the directory /app/db/ is writeable by the webserver. If you want to use MySQL, check config_default.php for the proper values.

 You're done with the server.

Create the first user
---

 1. Visit the site with a webbrowser, you'll be prompted to create a user and password
 2. Append the generated hash line to config.php
 3. Now refresh the page in your browser, and you should be able to log in with the credentials you just created.


#### No authentication

If you want to deploy munkireport without authentication (because you run your own authentication method), add the following line to config.php
`$conf['auth']['auth_noauth'] = array();`

Select which modules to install on the client
---

By default munkireport will only install 2 basic reporting modules: 'machine' and 'reportdata'. If you want the client to report on more items, visit:

 `http://example.com/index.php?/install/dump_modules/config`

Paste the resulting `$conf['modules'] = array(...);` in your config.php file. Remove items that you don't need reporting on from the array (e.g. 'servermetrics', 'certificate' and 'service' only make sense when installed on a client that runs Mac OSX server).

Setting up a client manually
---

Now you can setup a client to test if all is ok:

1. Open Terminal.app
2. Type: `sudo /bin/bash -c "$(curl -s http://example.com/index.php?/install)"`


Setting up clients with munki
---

When the client reporting goes well, you can create an installer package using the following:

1. Create the installer `bash -c "$(curl http://example.com/index.php?/install)" bash -i ~/Desktop`
2. Run `/usr/local/munki/munkiimport ~/Desktop/munkireport-2.2.0.pkg` (changing the version number as needed).
3. Run `makecatalogs`, and be sure to add the newly imported package to a manifest.

Note: There is also an AutoPkg recipe for creating munkireport packages, you can read more on how to setup those on [Setup AutoPkg for munkireport](autopkg.md)


Advanced client setup
---

When Munkireport is installed on the client, 3 directories are generated:

1. `preflight.d` - this directory is used by munkireport to run scripts on preflight, it contains at least `submit.preflight`. Scripts that exit with a non-zero status will not abort the run.
3. `preflight_abort.d` - this directory is empty and can be used for additional scripts that check if managedsoftwareupdate should run. Scripts that exit with a non-zero status will abort the run.
4. `postflight.d` - this directory is empty and can be used for additional scripts that should run on postflight.

All scripts have a timeout limit of 10 seconds, after that they're killed.

Advanced server setup
---

### Running MunkiReport from a subdir

Munkireport should able to detect if it is running from a subdirectory. If automatic detection fails, you can also specify the subdirectory in config.php.
So if you want to run munkireport from http://munki.mysite.org/report/
add the following to config.php:

    $conf['subdirectory'] = '/report/';


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

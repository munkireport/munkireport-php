munkireport-php
===============

Contains Dockerfile and other needed files to create a munkireport-php image.

The main munkireport-php repository is here: [https://github.com/munkireport/munkireport-php](https://github.com/munkireport/munkireport-php)

Run instructions
================

First create a persistent volume for the database

```
docker volume create --name munkireport-db
```

Then start the MR-PHP container as follows:

```
docker run -d --name="munkireport" \
  -v munkireport-db:/var/munkireport/app/db \
  -p 80:80 \
  munkireport/munkireport-php:release-latest
```

Settings
=====================

Most variables are exposed for configuration via environment:

- `SITENAME`: The name of your site, displayed in the title.
- `MODULES`: A comma delimited list of enabled modules.
- `AUTH_METHODS`:  A comma delimited list of authentication methods.
- Etc.

Example:

```
docker run -d --name="munkireport" \
  -e "SITENAME=I ❤️ MunkiReport" \
  -e "MODULES=applications, ard, bluetooth, certificate, disk_report, displays_info, extensions, filevault_status, fonts, ibridge, installhistory, inventory, localadmin, managedinstalls, munkireport, network, power, printer, profile, security, usb, user_sessions, warranty" \
  -v munkireport-db:/var/munkireport/app/db \
  -p 80:80 \
  munkireport/munkireport-php:release-latest
```

See also [MunkiReport Configuration variables](https://github.com/munkireport/munkireport-php/wiki/Server-Configuration)

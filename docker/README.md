munkireport-php
===============

Contains Dockerfile and other needed files to create a munkireport-php image.

The main munkireport-php repository is here: [https://github.com/munkireport/munkireport-php](https://github.com/munkireport/munkireport-php)

Part of the Macadmins Docker project: [https://registry.hub.docker.com/u/macadmins](https://registry.hub.docker.com/u/macadmins)

Run instructions
================

First create a persistent volume

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

Environment Variables
=====================

The following variables are exposed for configuration via environment:

- `MR_SITENAME`: The name of your site, displayed in the title.
- `MR_MODULES`: A comma delimited list of enabled modules.

Settings
========

If you need further configuration, you can link in your own config file:

```
docker run -d --name="munkireport" \
-v munkireport-db:/var/munkireport/app/db \
-v /your/local/path/config.php:/var/munkireport/config.php \
-p 80:80 \
munkireport/munkireport-php:release-latest
```

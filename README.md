
munkireport-php
===============

master: [![Build Status](https://travis-ci.org/munkireport/munkireport-php.svg?branch=master)](https://travis-ci.org/munkireport/munkireport-php)  wip: [![Build Status](https://travis-ci.org/munkireport/munkireport-php.svg?branch=wip)](https://travis-ci.org/munkireport/munkireport-php)

![Dashboard view](https://github.com/munkireport/munkireport-php/wiki/assets/pics/dashboard.png)

Munkireport-php is a reporting client for [munki](https://github.com/munki/munki/). The previous version of munkireport is still available on googlecode: [munkireport-php](https://code.google.com/p/munkireport-php/).

This project is a complete rewrite from the previous version, which was a quick-and-dirty port from the original python based munkireport [https://code.google.com/p/munkireport/]. 

The project a work-in-progress, please check for updates regularly.

Features
---

* Quick overview of your mac fleet with a dashboard
* Get reports on many features (hardware types, disk usage, etc)
* Lightweight: only sends reports when facts have changed
* Responsive webdesign (looks good on large and small screens)
* [more features](https://github.com/munkireport/munkireport-php/wiki/Features)

Setup
---

Setup is easy, you could be running your own reportingserver within minutes! 

Please read the [install manual](https://github.com/munkireport/munkireport-php/wiki/Quick-demo)

System Requirements
---

Apart from munki clients doing reporting, Munkireport relies on:

### Serverside:

* A webserver (runs fine with Apache, IIS and nginx)
* php version 7.0 or higher with pdo-sqlite3 and libxml

### Clientside

* a Modern webbrowser

Mailinglists
---

For questions about using munkireport or setting up munkireport, you can use:

http://groups.google.com/group/munkireport

For developers who want to contribute to the project:

http://groups.google.com/group/munkireport-dev

Contributing
---

If you want to contribute to munkireport2, please 

* read about [Localizing](docs/localize.md) in the docs folder
* check the [modules overview](https://github.com/munkireport/munkireport-php/wiki/Module-Overview) for info about installing and creating modules
* fork the [wip branch of repository](https://github.com/munkireport/munkireport-php/tree/wip)
* create a feature branch
* send a pull request with your changes.

External projects
---

Munkireport-php makes use of these fine software packages:

* [php](http://php.net) serverside scripting
* [CFPropertyList](https://github.com/rodneyrehm/CFPropertyList) serverside plist parsing
* [phpass 0.3.5](https://github.com/hautelook/phpass) for encrypting passwords
* [phpserialize](https://github.com/sdfsdhgjkbmnmxc/phpserialize) for serializing client data
* [jQuery](http://jquery.com) for easy javascript
* [Datatables](http://datatables.net) table display
* [nvd3](https://github.com/nvd3-community/nvd3) for graphs
* [Moment.js](http://momentjs.com) for displaying time
* [Bootstrap 3.0](http://getbootstrap.com) the main webframework
* [Font Awesome](http://fortawesome.github.io/Font-Awesome/) for icons
* [adLDAP](https://github.com/Adldap2/Adldap2) for authenticating against AD
* [i18next](http://i18next.com) js library for localization
* [libgsx](https://github.com/filipp/gsxlib) libgsx library used for GSX integration 



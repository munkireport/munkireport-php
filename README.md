
MunkiReport
===============

master: [![Build Status](https://travis-ci.org/munkireport/munkireport-php.svg?branch=master)](https://travis-ci.org/munkireport/munkireport-php)  wip: [![Build Status](https://travis-ci.org/munkireport/munkireport-php.svg?branch=wip)](https://travis-ci.org/munkireport/munkireport-php)

MunkiReport is a reporting client for macOS. While originally dependent on [Munki](https://github.com/munki/munki/), MunkiReport is now able to run stand-alone or to be coupled with Munki, Jamf or other macOS management solutions.

![Dashboard view](https://github.com/munkireport/munkireport-php/wiki/assets/pics/dashboard.png)

Features
---

* Quick overview of your macOS fleet with a customizable dashboards
* Get reports on many features (hardware types, disk usage, etc)
* Extendable with a growing list of [modules](https://github.com/munkireport/munkireport-php/wiki/Modules)
* Lightweight: only sends reports when facts have changed
* Responsive webdesign
* [more features](https://github.com/munkireport/munkireport-php/wiki/Features)

Setup
---

Setup is easy, you could be running your own reporting server within minutes! 

Please read the [demonstration setup](https://github.com/munkireport/munkireport-php/wiki/Quick-demo).

System Requirements
---

### Serverside:

* A webserver (runs fine with Apache, IIS and nginx)
* php version 7.2.5 or higher with pdo-sqlite3 and libxml

### Clientside

* a Modern webbrowser

Mailing lists
---

For questions about using MunkiReport or setting up MunkiReport, you can use:

https://groups.google.com/group/munkireport

For developers who want to contribute to the project:

https://groups.google.com/group/munkireport-dev

For security related issues, please use the private mailinglist:

https://groups.google.com/group/munkireport-security


Contributing
---

If you want to contribute to MunkiReport, please 

* read about [Localizing](docs/localize.md) in the docs folder
* check the [modules overview](https://github.com/munkireport/munkireport-php/wiki/Module-Overview) for info about installing and creating modules
* fork the [wip branch of repository](https://github.com/munkireport/munkireport-php/tree/wip)
* create a feature branch
* send a pull request with your changes

External projects
---

MunkiReport makes use of these fine software packages:

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



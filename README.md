munkireport-php
===============

![Dashboard view](https://raw.github.com/wiki/munkireport/munkireport-php/assets/pics/dashboard.png)

This is version 2 of munkireport-php, a reporting client for [munki](https://code.google.com/p/munki/). The previous version of munkireport is still available on googlecode: [munkireport-php](https://code.google.com/p/munkireport-php/).
I moved the project to github because github is just awesome!

This project is a complete rewrite from the previous version, which was a quick-and-dirty port from the original python based munkireport [https://code.google.com/p/munkireport/]. 

The project is still a work-in-progress, although I'm using it in a production environment for quite some time now.

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

Please read the [install manual](docs/setup.md)

System Requirements
---

Apart from munki clients doing reporting, Munkireport relies on:

### Serverside:

* A webserver (runs fine with Apache, IIS and nginx)
* php version 5 with pdo-sqlite3 and libxml

### Clientside

* a Modern webbrowser
* For persistent storage (sorting and search in datatables) you need a browser that supports [localStorage](http://caniuse.com/#search=localstorage)

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
* fork the [wip branch of repository](https://github.com/munkireport/munkireport-php/tree/wip)
* create a feature branch
* send a pull request with your changes.


External projects
---

Munkireport-php makes use of these fine software packages:

* [php](http://php.net) serverside scripting
* [CFPropertyList](https://github.com/rodneyrehm/CFPropertyList) serverside plist parsing
* [phpass 0.3](http://www.openwall.com/phpass/) for encrypting passwords
* [phpserialize](https://github.com/sdfsdhgjkbmnmxc/phpserialize) for serializing client data
* [jQuery](http://jquery.com) for easy javascript
* [Datatables](http://datatables.net) table display
* [Flotr2](http://www.humblesoftware.com/flotr2/) for graphs
* [Moment.js](http://momentjs.com) for displaying time
* [Bootstrap 3.0](http://getbootstrap.com) the main webframework
* [Font Awesome](http://fortawesome.github.io/Font-Awesome/) for icons
* [adLDAP](http://adldap.sourceforge.net) for authenticating against AD
* [i18next](http://i18next.com) js library for localization


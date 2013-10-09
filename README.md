munkireport-php
===============

![Dashboard view](https://raw.github.com/wiki/bochoven/munkireport-php/assets/pics/dashboard.png)

This is version 2 of munkireport-php

It is still a work-in-progress, although I'm using it in a production environment for quite some time now.


Mailinglist
---

http://groups.google.com/group/munkireport

For developers:

http://groups.google.com/group/munkireport-dev

System Requirements
---

Apart from munki clients doing reporting, Munkireport relies on:

### Serverside:

* A webserver (runs fine with Apache, IIS and nginx)
* php version 5 with pdo-sqlite3

### Clientside

* a Modern webbrowser
* For persistent storage (sorting and search in datatables) you need a browser that supports [localStorage](http://caniuse.com/#search=localstorage)

External projects
---

Munkireport-php makes use of these fine software packages:

* [jQuery](http://jquery.com) for easy javascript
* [Datatables](http://datatables.net) table display
* [Flotr2](http://www.humblesoftware.com/flotr2/) for graphs
* [Moment.js](http://momentjs.com) for displaying time
* [Bootstrap 3.0](http://getbootstrap.com) the main webframework
* [Font Awesome](http://fortawesome.github.io/Font-Awesome/) for icons


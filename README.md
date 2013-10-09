munkireport-php
===============

![Dashboard view](https://raw.github.com/wiki/bochoven/munkireport-php/assets/pics/dashboard.png)

This is version 2 of munkireport-php, a reporting client for [munki](https://code.google.com/p/munki/). The previous version of munkireport is still available on googlecode: [munkireport-php](https://code.google.com/p/munkireport-php/).
I moved the project to github because I like the way you can branch and merge (and it does not hurt my eyes so bad).

This project is still a work-in-progress, although I'm using it in a production environment for quite some time now.

Installation
---

Please read the [install manual](docs/setup.md)

System Requirements
---

Apart from munki clients doing reporting, Munkireport relies on:

### Serverside:

* A webserver (runs fine with Apache, IIS and nginx)
* php version 5 with pdo-sqlite3

### Clientside

* a Modern webbrowser
* For persistent storage (sorting and search in datatables) you need a browser that supports [localStorage](http://caniuse.com/#search=localstorage)

Mailinglists
---

For questions about using munkireport or setting up munkireport, you can use:

http://groups.google.com/group/munkireport

For developers who want to contribute to the project:

http://groups.google.com/group/munkireport-dev

External projects
---

Munkireport-php makes use of these fine software packages:

* [php](http://php.net) serverside scripting
* [jQuery](http://jquery.com) for easy javascript
* [Datatables](http://datatables.net) table display
* [Flotr2](http://www.humblesoftware.com/flotr2/) for graphs
* [Moment.js](http://momentjs.com) for displaying time
* [Bootstrap 3.0](http://getbootstrap.com) the main webframework
* [Font Awesome](http://fortawesome.github.io/Font-Awesome/) for icons


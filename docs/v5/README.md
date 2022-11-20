Documentation
=============

The documentation of munkireport is a work in progresss, if you want to contribute, please fork the wiki and send a pull request.

Installation
---

The installation is covered in setup.md

Performance
---

Running munkireport with up to 400 (300 daily active) clients with the SQLite backend should be ok. If you're adding more clients you'll need a database backend that allows for more concurrency like MySQL.

Security
---

Although munkireport should be pretty secure out-of-the-box, there are some areas where you could improve the security.

#### Use https for the webserver

Logging into the munkireport webapp sends your username and password in cleartext, someone might intercept your credentials and use them to 
login. If you use https, the communication between your browser and the webserver is encrypted.

#### Remove the database from the documentroot

Add instructions.

#### Remove the webapp from the documentroot

Add instructions.

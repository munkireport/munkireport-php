MRMailer module
==============

A magical module that sends emails when clients with error or other criteria check in. Module is powered by PHPMailer - [https://github.com/PHPMailer/PHPMailer](https://github.com/PHPMailer/PHPMailer)

This module does not have a table and does not have a client side element, it should be invoked by other modules. If you want a module or action to send an email, provide feedback to the \#munkireport channel of the MacAdmins Slack group.

Before the module can be used, you must set the config options in config.php. Module currently supports sending emails via SMTP only at this time.

Config Options
---
* $conf['email']['enabled'] = true; // Controls if emails are sent or not
* $conf['email']['use_smtp'] = true; // Should always be true
* $conf['email']['from'] = array('noreply@example.com' => 'MunkiReport Mailer'); // Required
* $conf['email']['to'] = array('user1@example.com' => 'MunkiReport Mailer','user2@example.com' => 'MunkiReport Mailer'); // Required, can have more than one
* $conf['email']['replyto'] = array('noreply@example.com' => 'MunkiReport Mailer'); // Can have more than one
* $conf['email']['cc'] = array('user1@example.com' => 'MunkiReport Mailer'); // Can have more than one
* $conf['email']['bcc'] = array('user1@example.com' => 'MunkiReport Mailer'); // Can have more than one
* $conf['email']['smtp_host'] = 'smtp1.example.com'; // Required if using SMTP
* $conf['email']['smtp_auth'] = true; // Required if using SMTP, can be true or false
* $conf['email']['smtp_username'] = 'user@example.com'; // Required if smtp_auth is true
* $conf['email']['smtp_password'] = 'secret'; // Required if smtp_auth is true
* $conf['email']['smtp_secure'] = 'tls'; // Required if using SMTP, can be "tls", "ssl", or ""
* $conf['email']['smtp_port'] = 587; // Required if using SMTP
* $conf['email']['locale'] = 'en'; // Local used for error messages, defaults to English
* $conf['email']['debug'] = 0; //Debug level, 0-5
* $conf['email']['use_html'] = true; //Send HTML email
* $conf['email']['skip_serials'] = array('A00AB0A0AB00','A00AB0A0BC11'); // Serial number of Macs to never send an email for
* $conf['email']['skip_subjects'] = array('Checked In','Disk is full'); // Subjects to never send an email for


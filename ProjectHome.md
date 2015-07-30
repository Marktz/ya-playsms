_Development on the original [PlaySMS](http://playsms.sourceforge.net/) seems to have died.  This is a resurrection of the project._

PlaySMS is a flexible Web-based Mobile Portal System that it can be made to fit to various services such as an SMS gateway, personal messaging systems, corporate and group communication tools

## News ##
  * 2008-Mar-01 - [v0.8.4 release](v084_Release_Notes.md)
  * 2008-Feb-07 - [v0.8.3 relased](v083_Release_Notes.md)
  * 2008-Jan-09 - [v0.8.2 released](v082_Release_Notes.md)
  * 2008-Jan - branched from original PlaySms

## Current Features ##

  * Send SMS to single mobile phone (web2mobile)
  * Send SMS broadcasted (bulk SMS) to a group of mobile phones (web2mobiles)
  * SMS autoreply, for easy autoreplying formatted incoming SMS
  * SMS board, forward received SMS to email,html and/or xml page
  * SMS command, execute server side shell script using SMS
  * SMS custom, forward incoming SMS to custom SMS application
  * SMS poll, manage polling system using SMS

  * Support sending flash and unicode message
  * Receive private SMS to Inbox (mobile2web)
  * Forward single SMS from mobile phone to a group of mobile phones (mobile2mobiles)
  * Simple webservices for sending SMS and retrieving delivery reports
  * Create your own gateway module other than Gnokii, Kannel or sms server Clickatell
  * Easy webbased control panel


## Requirements ##

  * Works on any OS, however [Ubuntu](http://ubuntu.com) is preferred
  * Web Server ([Apache](http://httpd.apache.org) preferred)
  * Database Server ([MySql](http://www.mysql.com) preferred)
  * [PHP](http://www.php.net) 5.2+

## Supported Gateway Modules ##

  * [Clickatell](http://www.clickatell.com)
  * [Gnokii](http://www.gnokii.org)
  * [Kannel](http://www.kannel.org)
  * Uplink
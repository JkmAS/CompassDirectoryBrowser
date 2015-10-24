COMPASS directory browser
=========================
![stable](https://img.shields.io/badge/stable-0.9.5-blue.svg) ![license](https://img.shields.io/badge/license-MIT-brightgreen.svg) 

<p align="center">
  <img src="https://github.com/JkmAS/CompassDirectoryBrowser/blob/master/promo.png" alt="Compass Directory browser"/>
</p>

About
-----
COMPASS is a simple directory browser based on PHP, which allows you to browse 
folders and files in your web server. The big advantage is simple installation, 
which consists of copying one file.

Optional functionality is logging access of users of application and 
authorizations of access only from specified IP addresses.

About extended version
-----

This <a href="https://github.com/JkmAS/CompassDirectoryBrowser/tree/extended">extended version</a> creates coauthors. Version provides additional 
functionality compared to the basic version. C

Installation
------------
First read please Security!

  1. Copy `compass.php` to the place, where you want to browse files and folders
  2. Enter `yourdomain.com/pathtoapp/compass.php`
  3. Enjoy it
  
Optional settings:

Logging
  4. Uncomment logging: `new AccessLogger()`
  5. Set path to logfile `const PATH_TO_LOG_FILE = ''`
  6. Set name of logfile `const LOG_FILE_NAME = 'compass.log'`, by default is set to compass.log

Access only from specified IP addresses
  7. Set IP addresses with access to the application, separate by comma: `const IP_ADDRESS_WITH_PERMISSION = ''`


Features
--------

  * Scans directories and lists the content
  * Shows the permissions, size and last modification of files
  * Suitable for browsing on your mobile
  * Simple installation
  * It looks like Ubuntu Terminal
  * Logging
  * Access only from specified IP addresses

Logfile
-------
If you turn on logging, accesses log this way: datetime, type (info - information,
warn - security threat, danger - access from unauthorized IP), IP address and user request.

On the LAMP server (Linux) is access to the logfile restricted. On the WAMP (Windows)
access is not limited!

Requirements
------------

  * PHP 5.4+
  * Javascript enabled

Support of browsers
-------------------

It works on all versions of current browsers.

Browser  | version
-------- | -------
IE       | 9+
Firefox  | 20+
Chrome   | 24+
Safari   | 4+
Opera    | 10+

Security
--------
Application are using at your own risk! The author is not responsible for damage 
caused by using the application.

Application lists the content of directories and makes available it to all from 
the Internet. Be careful with placement of application!

Compass allows you to browse only the files in the current directory and 
subdirectories. But there is no 100% guarantee that the attacker finds a bug in 
the application and enables browsing other directories.

Access from IP addresses is restricted due to safety concerns. 
Full functionality is not guaranteed.

Version
------

  * 1.0.0 - preparing - update option 
  * 0.9.5 - current - logging
  * 0.9.0 - initial release

Author
------

Created by [JkmAS Mejstrik](http://www.jkmas.cz)

License
-------

COMPASS is distributed under the MIT License.

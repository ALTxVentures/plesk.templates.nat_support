You can change the settings of virtual hosts running on the Panel server, for example, set custom error pages (similar for all virtual hosts), or change the port on which the hosted site is available.

To reduce the risk of errors during modification of configuration files, Parallels Plesk Panel provides a mechanism for changing virtual host configuration - configuration templates. Before 11.0 Panel had templates only for Apache configuration files, but with adding support for nginx administrators can modify nginx templates as well. Read more about the how Apache and nginx work together in the Administrator's Guide, section Improving Web Server Performance with nginx (Linux).

RESOURCES
=========
* [Changing Virtual Hosts Settings Using Configuration Templates](http://download1.parallels.com/Plesk/PP11/11.0/Doc/en-US/online/plesk-linux-advanced-administration-guide/index.htm?fileName=68693.htm)
* [Apache Configuration Variables](http://download1.parallels.com/Plesk/PP11/11.0/Doc/en-US/online/plesk-linux-advanced-administration-guide/index.htm?fileName=68713.htm)
* [Configuration Templates Structure](http://download1.parallels.com/Plesk/PP11/11.0/Doc/en-US/online/plesk-linux-advanced-administration-guide/index.htm?fileName=68820.htm)

NOTES
=====
* run ```/usr/local/psa/admin/bin/httpdmng --reconfigure-all``` after changes are made to recompile all existing configs
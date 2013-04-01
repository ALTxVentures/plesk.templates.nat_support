<?php
    $atmailDocroot = $VAR->server->webserver->atmailcom->docroot;
    $domainsBootstrap = $VAR->domainsWebmailAtmailcomBootstrap;
    $atmailConfD = "/etc/psa-webmail/atmailcom";

?>

<VirtualHost *:<?php echo $VAR->server->webserver->httpPort ?>>
	ServerName atmailcom.webmail
	ServerAlias atmailcom.webmail.*
        ServerAdmin "<?php echo $VAR->server->admin->email ?>"

        Include "<?php echo $domainsBootstrap ?>*"
	UseCanonicalName Off

    DocumentRoot "<?php echo $atmailDocroot ?>"
    Alias /atmailcom/ "<?php echo $atmailDocroot ?>"
	CustomLog /var/log/atmail/access_com_log plesklog
	ErrorLog /var/log/atmail/error_com_log

    <?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

	<Directory "<?php echo $atmailDocroot ?>">
        <IfModule <?php echo $VAR->server->webserver->apache->php4ModuleName ?>>
            php_admin_flag engine on
            php_admin_flag safe_mode off
            php_admin_flag magic_quotes_gpc off
            php_admin_flag register_globals off

            php_admin_value open_basedir "<?php echo
                "$atmailDocroot/:/var/log/atmail/:$atmailConfD/:/tmp/:/var/tmp/" ?>"
            php_admin_value include_path "<?php echo
                "$atmailDocroot:$atmailDocroot/libs:$atmailDocroot/libs/Atmail:$atmailDocroot/libs/PEAR:$atmailDocroot/libs/File:." ?>"

            php_admin_value upload_max_filesize 16M
            php_admin_value post_max_size 16M
        </IfModule>

        <IfModule mod_php5.c>
            php_admin_flag engine on
            php_admin_flag safe_mode off
            php_admin_flag magic_quotes_gpc off
            php_admin_flag register_globals off

            php_admin_value open_basedir "<?php echo
                "$atmailDocroot/:/var/log/atmail/:$atmailConfD/:/tmp/:/var/tmp/" ?>"
            php_admin_value include_path "<?php echo
                "$atmailDocroot:$atmailDocroot/libs:$atmailDocroot/libs/Atmail:$atmailDocroot/libs/PEAR:$atmailDocroot/libs/File:." ?>"

            php_admin_value upload_max_filesize 16M
            php_admin_value post_max_size 16M
        </IfModule>

        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>

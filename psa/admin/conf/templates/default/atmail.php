#ATTENTION!
#
#DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY,
#SO ALL YOUR CHANGES WILL BE LOST THE NEXT TIME THE FILE IS GENERATED.

<?php
    $ipAddresses = $VAR->server->ipAddresses->all;
    $ipLimit = $VAR->server->webserver->apache->vhostIpCapacity;
    $atmailDocroot = $VAR->server->webserver->atmail->docroot;
    $atmailConfD = "/etc/psa-webmail/atmail";
    $domainsBootstrap = $VAR->domainsWebmailAtmailBootstrap;
    $modPHPAvailiable = $VAR->server->php->ModAvailable;

?>

<?php
    for($ipAddress = reset($ipAddresses);
        $ipAddress;
        $ipAddress = next($ipAddresses)):
?>
<VirtualHost \
    <?php echo "{$ipAddress->escapedAddress}:{$VAR->server->webserver->httpPort}" ?> \
    <?php for ($n = 1;
            $n < $ipLimit && $ipAddress = next($ipAddresses);
            ++$n):
    ?>
    <?php echo "{$ipAddress->escapedAddress}:{$VAR->server->webserver->httpPort}" ?> \
    <?php endfor; ?>
    <?php echo ($VAR->server->webserver->proxyActive) ? "127.0.0.1:" . $VAR->server->webserver->httpPort : ''; ?> \
    >
	ServerName atmail.webmail
	ServerAlias atmail.webmail.*
        ServerAdmin "<?php echo $VAR->server->admin->email ?>"

        Include "<?php echo $domainsBootstrap ?>*"
	UseCanonicalName Off

    DocumentRoot "<?php echo $atmailDocroot ?>"
    Alias /atmail/ "<?php echo $atmailDocroot ?>"
	CustomLog /var/log/atmail/access_log plesklog
	ErrorLog /var/log/atmail/error_log

    <?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

	<Directory "<?php echo $atmailDocroot ?>">
    <?php if ($modPHPAvailiable): ?>
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
    <?php else: ?>

        SetHandler None
        AddHandler php-script .php
        Options +ExecCGI

    <?php endif; ?>

        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
<?php endfor; ?>

<IfModule mod_ssl.c>
<?php
    for($ipAddress = reset($ipAddresses);
        $ipAddress;
        $ipAddress = next($ipAddresses)):
?>
<?php if ($ipAddress->sslCertificate->ce): ?>
<VirtualHost \
    <?php echo "{$ipAddress->escapedAddress}:{$VAR->server->webserver->httpsPort}" ?> \
    <?php echo ($VAR->server->webserver->proxyActive) ? "127.0.0.1:" . $VAR->server->webserver->httpsPort : ''; ?> \
    >
    ServerName atmail.webmail
    ServerAlias atmail.webmail.*
    ServerAdmin "<?php echo $VAR->server->admin->email ?>"

    Include "<?php echo $domainsBootstrap ?>*"

	UseCanonicalName Off

    DocumentRoot "<?php echo $atmailDocroot ?>"
    Alias /atmail/ "<?php echo $atmailDocroot ?>"
	CustomLog /var/log/atmail/access_log plesklog
	ErrorLog /var/log/atmail/error_log

	SSLEngine on
	SSLVerifyClient none
    SSLCertificateFile "<?php echo $ipAddress->sslCertificate->ceFilePath ?>"

    <?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

	<Directory "<?php echo $atmailDocroot ?>">
    <?php if ($modPHPAvailiable): ?>
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
    <?php else: ?>

        SetHandler None
        AddHandler php-script .php
        Options +ExecCGI

    <?php endif; ?>

		SSLRequireSSL

		Order allow,deny
		Allow from all
	</Directory>
</VirtualHost>
<?php endif; ?>
<?php endfor; ?>

</IfModule>

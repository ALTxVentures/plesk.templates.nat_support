#ATTENTION!
#
#DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY,
#SO ALL YOUR CHANGES WILL BE LOST THE NEXT TIME THE FILE IS GENERATED. 

<?php include('/usr/local/psa/admin/conf/templates/custom/lib/nat_resolve.inc.php');?>

<?php
    $ipAddresses = $VAR->server->ipAddresses->all;
    $ipLimit = $VAR->server->webserver->apache->vhostIpCapacity;
    $hordeDocroot = $VAR->server->webserver->horde->docroot;
    $domainsBootstrap = $VAR->domainsWebmailHordeBootstrap;
    $hordeConfD = $VAR->server->webserver->horde->confD;
    $hordeLogD = $VAR->server->webserver->horde->logD;
    $hordeDocD = $VAR->server->webserver->horde->docD;
    $pearDataD = $VAR->server->webserver->horde->dataD;
    $modPHPAvailiable = $VAR->server->php->ModAvailable;
?>

<?php
    for($ipAddress = reset($ipAddresses);
        $ipAddress;
        $ipAddress = next($ipAddresses)):
?>
<VirtualHost \
	<?php 
            $ip['public'] = $ipAddress->escapedAddress;
            $ip['private'] = nat_resolve($ipAddress->escapedAddress);

            if ( $ip['private']!= null ):
                foreach ($ip AS $ipaddress):
        ?>
    <?php echo $ipaddress.":{$VAR->server->webserver->httpPort}" ?> \
    <?php endforeach; ?>
	<?php endif; ?>
    <?php for ($n = 1;
            $n < $ipLimit && $ipAddress = next($ipAddresses);
            ++$n):
    ?>
    <?php 
            $ip['public'] = $ipAddress->escapedAddress;
            $ip['private'] = nat_resolve($ipAddress->escapedAddress);

            if ( $ip['private']!= null ):
                foreach ($ip AS $ipaddress):
        ?>
    <?php echo $ipaddress.":{$VAR->server->webserver->httpPort}" ?> \
    <?php endforeach; ?>
    <?php endif; ?>
    <?php endfor; ?>
    <?php echo ($VAR->server->webserver->proxyActive) ? "127.0.0.1:" . $VAR->server->webserver->httpPort : ''; ?>>

	ServerName horde.webmail
	ServerAlias horde.webmail.*
        ServerAdmin "<?php echo $VAR->server->admin->email ?>"

        Include "<?php echo $domainsBootstrap ?>*"
	UseCanonicalName Off

	DocumentRoot "<?php echo $hordeDocroot ?>"
	Alias /horde/ "<?php echo $hordeDocroot ?>"
	Alias /imp/ "<?php echo $hordeDocroot ?>/imp/"

    <?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

	<Directory "<?php echo $hordeDocroot ?>">
    <?php if ($modPHPAvailiable): ?>
        <IfModule <?php echo $VAR->server->webserver->apache->php4ModuleName ?>>
			php_admin_flag engine on
			php_admin_flag magic_quotes_gpc off
			php_admin_flag safe_mode off

			php_admin_value upload_tmp_dir "/tmp/"
			php_admin_value open_basedir "<?php
                echo "$hordeDocroot:$hordeConfD/:/tmp/:/var/tmp/:$hordeLogD:$hordeDocD/:$pearDataD/" ?>"

			php_admin_value include_path "<?php
                echo "$hordeDocroot:$hordeDocroot/lib:$pearDataD:." ?>"
		</IfModule>

		<IfModule mod_php5.c>
			php_admin_flag engine on
			php_admin_flag magic_quotes_gpc off
			php_admin_flag safe_mode off

			php_admin_value upload_tmp_dir "/tmp/"
			php_admin_value open_basedir "<?php
                echo "$hordeDocroot:$hordeConfD/:/tmp/:/var/tmp/:$hordeLogD:$hordeDocD/:$pearDataD/" ?>"
			php_admin_value include_path "<?php
                echo "$hordeDocroot:$hordeDocroot/lib:$pearDataD:." ?>"
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
	<?php 
            $ip['public'] = $ipAddress->escapedAddress;
            $ip['private'] = nat_resolve($ipAddress->escapedAddress);

            if ( $ip['private']!= null ):
                foreach ($ip AS $ipaddress):
        ?><?php echo nat_resolve($ipAddress->escapedAddress).":{$VAR->server->webserver->httpsPort}" ?>
    <?php endforeach; ?>
	<?php endif; ?>
    <?php echo ($VAR->server->webserver->proxyActive) ? "127.0.0.1:" . $VAR->server->webserver->httpsPort : ''; ?>>

	ServerName horde.webmail
	ServerAlias horde.webmail.*
    ServerAdmin "<?php echo $VAR->server->admin->email ?>"

    Include "<?php echo $domainsBootstrap ?>*"

	UseCanonicalName Off

    DocumentRoot "<?php echo $hordeDocroot ?>"
	Alias /horde/ "<?php echo $hordeDocroot ?>"
	Alias /imp/ "<?php echo $hordeDocroot ?>/imp/"

	SSLEngine on
	SSLVerifyClient none
    SSLCertificateFile "<?php echo $ipAddress->sslCertificate->ceFilePath ?>"

    <?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

	<Directory "<?php echo $hordeDocroot ?>">
<?php if ($modPHPAvailiable): ?>
        <IfModule <?php echo $VAR->server->webserver->apache->php4ModuleName ?>>
			php_admin_flag engine on
			php_admin_flag magic_quotes_gpc off
			php_admin_flag safe_mode off

			php_admin_value upload_tmp_dir "/tmp/"
			php_admin_value open_basedir "<?php
                echo "$hordeDocroot:$hordeConfD/:/tmp/:/var/tmp/:$hordeLogD:$hordeDocD/:$pearDataD/" ?>"

			php_admin_value include_path "<?php
                echo "$hordeDocroot:$hordeDocroot/lib:$pearDataD:." ?>"
		</IfModule>

		<IfModule mod_php5.c>
			php_admin_flag engine on
			php_admin_flag magic_quotes_gpc off
			php_admin_flag safe_mode off

			php_admin_value upload_tmp_dir "/tmp/"
			php_admin_value open_basedir "<?php
                echo "$hordeDocroot:$hordeConfD/:/tmp/:/var/tmp/:$hordeLogD:$hordeDocD/:$pearDataD/" ?>"
			php_admin_value include_path "<?php
                echo "$hordeDocroot:$hordeDocroot/lib:$pearDataD:." ?>"
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

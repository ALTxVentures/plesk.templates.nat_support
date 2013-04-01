<VirtualHost *:<?php echo $VAR->server->webserver->httpPort ?>>
        DocumentRoot "<?php echo $VAR->server->webserver->httpDir ?>"
		ServerName lists
		ServerAlias lists.*
		UseCanonicalName Off

<?php foreach ($VAR->server->mailman->scriptAliases as $urlPath => $filePath): ?>
        ScriptAlias "<?php echo $urlPath ?>" "<?php echo $filePath ?>"
<?php endforeach; ?>

<?php foreach ($VAR->server->mailman->aliases as $urlPath => $filePath): ?>
        Alias "<?php echo $urlPath ?>" "<?php echo $filePath ?>"
<?php endforeach; ?>

	SSLEngine off

<?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

	<Directory <?php echo $VAR->server->mailman->varDir ?>/archives/>
            Options FollowSymLinks
            Order allow,deny
            Allow from all
        </Directory>

    </VirtualHost>

<IfModule mod_ssl.c>
<VirtualHost *:<?php echo $VAR->server->webserver->httpsPort ?>>
        DocumentRoot "<?php echo $VAR->server->webserver->httpsDir ?>"
		ServerName lists
		ServerAlias lists.*
		UseCanonicalName Off

<?php foreach ($VAR->server->mailman->scriptAliases as $urlPath => $filePath): ?>
        ScriptAlias "<?php echo $urlPath ?>" "<?php echo $filePath ?>"
<?php endforeach; ?>

<?php foreach ($VAR->server->mailman->aliases as $urlPath => $filePath): ?>
        Alias "<?php echo $urlPath ?>" "<?php echo $filePath ?>"
<?php endforeach; ?>

	SSLEngine on
	SSLVerifyClient none
	SSLCertificateFile "<?php echo $VAR->server->defaultSslCertificate->ceFilePath ?>"

<?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

	<Directory <?php echo $VAR->server->mailman->varDir ?>/archives/>
            Options FollowSymLinks
            Order allow,deny
            Allow from all
        </Directory>

    </VirtualHost>
</IfModule>

<?php if ($OPT['ssl']) : ?>
<IfModule mod_ssl.c>
<?php endif; ?>

    <VirtualHost *:<?php echo $OPT['ssl'] ? $VAR->server->webserver->httpsPort : $VAR->server->webserver->httpPort ?>>
        ServerName "default"
        UseCanonicalName Off
        DocumentRoot "<?php echo $OPT['ssl'] ? $VAR->server->webserver->httpsDir : $VAR->server->webserver->httpDir ?>"
        ScriptAlias /cgi-bin/ "<?php echo $VAR->server->webserver->cgiBinDir ?>"

<?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

<?php if ($OPT['ssl']) : ?>
<?php if ($VAR->server->defaultSslCertificate->ce): ?>
        SSLEngine on
        SSLVerifyClient none
        SSLCertificateFile "<?php echo $VAR->server->defaultSslCertificate->ceFilePath ?>"

<?php if ($VAR->server->defaultSslCertificate->ca): ?>
        SSLCACertificateFile "<?php echo $VAR->server->defaultSslCertificate->caFilePath ?>"
<?php endif; ?>
<?php endif; ?>
<?php else: ?>
        SSLEngine off
<?php endif; ?>

        <Directory "<?php echo $VAR->server->webserver->cgiBinDir ?>">
            AllowOverride None
            Options None
            Order allow,deny
            Allow from all
        </Directory>

        <Directory <?php echo $OPT['ssl'] ? $VAR->server->webserver->httpsDir : $VAR->server->webserver->httpDir ?>>

<?php echo $VAR->includeTemplate('service/php.php', array(
    'enabled' => true,
    'safe_mode' => true,
    'dir' => $OPT['ssl'] ? $VAR->server->webserver->httpsDir : $VAR->server->webserver->httpDir,
)) ?>

        </Directory>

    </VirtualHost>

<?php if ($OPT['ssl']) : ?>
</IfModule>
<?php endif; ?>

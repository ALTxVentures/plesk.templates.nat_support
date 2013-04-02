<?php include('/usr/local/psa/admin/conf/templates/custom/lib/nat_resolve.inc.php');?>

<?php if ($OPT['ssl']) : ?>
<IfModule mod_ssl.c>
<?php endif; ?>

<?php for($ipAddresses = $VAR->server->ipAddresses->all, $ipAddress = reset($ipAddresses); $ipAddress; $ipAddress = next($ipAddresses)): ?>
    <VirtualHost \
     <?php 
            $ip['public'] = $ipAddress->escapedAddress;
            $ip['private'] = nat_resolve($ipAddress->escapedAddress);

            if ( $ip['private']!= null ):
                foreach ($ip AS $ipaddress):
        ?><?php echo $ipaddress ?>:<?php echo $OPT['ssl'] ? $VAR->server->webserver->httpsPort : $VAR->server->webserver->httpPort ?> \
        <?php endforeach; ?>
        <?php endif; ?>
<?php for ($n = 1; $n < $OPT['ipLimit'] && $ipAddress = next($ipAddresses); $n++): ?>
        <?php 
            $ip['public'] = $ipAddress->escapedAddress;
            $ip['private'] = nat_resolve($ipAddress->escapedAddress);

            if ( $ip['private']!= null ):
                foreach ($ip AS $ipaddress):
        ?>
        <?php echo $ipaddress ?>:<?php echo $OPT['ssl'] ? $VAR->server->webserver->httpsPort : $VAR->server->webserver->httpPort ?> \
        <?php endforeach; ?>
        <?php endif; ?>
<?php endfor; ?>
        <?php if ($VAR->server->webserver->proxyActive) echo '127.0.0.1:' . ($OPT['ssl'] ? $VAR->server->webserver->httpsPort : $VAR->server->webserver->httpPort) ?>
    >
        ServerName "default<?php echo 1 == $OPT['ipLimit'] ? '-' . str_replace(array('.', ':'), array('_', '_'), $ipAddress->address) : '' ?>"
        UseCanonicalName Off
        DocumentRoot "<?php echo $OPT['ssl'] ? $VAR->server->webserver->httpsDir : $VAR->server->webserver->httpDir ?>"
        ScriptAlias /cgi-bin/ "<?php echo $VAR->server->webserver->cgiBinDir ?>"

<?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

<?php if ($OPT['ssl']) : ?>
<?php if ($ipAddress->sslCertificate->ce): ?>
        SSLEngine on
        SSLVerifyClient none
        SSLCertificateFile "<?php echo $ipAddress->sslCertificate->ceFilePath ?>"

<?php if ($ipAddress->sslCertificate->ca): ?>
        SSLCACertificateFile "<?php echo $ipAddress->sslCertificate->caFilePath ?>"
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
<?php endfor; ?>

<?php if ($OPT['ssl']) : ?>
</IfModule>
<?php endif; ?>

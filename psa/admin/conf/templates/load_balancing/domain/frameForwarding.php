<VirtualHost *:<?php echo $OPT['ssl'] ? $VAR->server->webserver->httpsPort : $VAR->server->webserver->httpPort ?>>
    ServerName "<?php echo $VAR->domain->asciiName ?>"
    ServerAlias  "www.<?php echo $VAR->domain->asciiName ?>"
<?php foreach ($VAR->domain->webAliases as $alias): ?>
    ServerAlias "<?php echo  $alias->asciiName ?>"
    ServerAlias "www.<?php echo $alias->asciiName ?>"
<?php endforeach; ?>

<?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

<?php if (array_key_exists('serverAdmin', $OPT) && $OPT['serverAdmin']): ?>
    ServerAdmin  "<?php echo $OPT['serverAdmin'] ?>"
<?php endif; ?>

    DocumentRoot "<?php echo $VAR->domain->physicalHosting->vhostDir ?>/httpdocs"
    <IfModule mod_ssl.c>
        SSLEngine off
    </IfModule>
</VirtualHost>

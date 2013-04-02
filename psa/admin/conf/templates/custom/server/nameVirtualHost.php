<?php include('/usr/local/psa/admin/conf/templates/custom/lib/nat_resolve.inc.php');?>

<?php foreach ($VAR->server->ipAddresses->all as $ipaddress): ?>

<?php 
    $ip['public'] = $ipaddress->escapedAddress;
    $ip['private'] = nat_resolve($ipaddress->escapedAddress);

    if ( $ip['private']!= null ):
        foreach ($ip AS $ip_address):
?>

NameVirtualHost <?php echo $ip_address ?>:<?php
    echo ($OPT['ssl']
        ? $VAR->server->webserver->httpsPort
        : $VAR->server->webserver->httpPort) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
<?php endforeach; ?>

<?php if ($VAR->server->webserver->proxyActive): ?>
NameVirtualHost 127.0.0.1:<?php
    echo ($OPT['ssl']
        ? $VAR->server->webserver->httpsPort
        : $VAR->server->webserver->httpPort) . "\n" ?>
<?php endif ?>

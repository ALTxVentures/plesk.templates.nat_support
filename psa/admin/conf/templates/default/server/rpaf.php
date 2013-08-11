<IfModule <?php echo $OPT['mod'] ?>>
    RPAFproxy_ips <?php foreach($VAR->server->ipAddresses->all as $ipAddress) { echo ' ' . $ipAddress->escapedAddress; } ?>

</IfModule>
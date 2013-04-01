#ATTENTION!
#
#DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY,
#SO ALL YOUR CHANGES WILL BE LOST THE NEXT TIME THE FILE IS GENERATED.

<IfModule mod_rpaf.c>
        RPAFenable On
        RPAFsethostname On
        RPAFproxy_ips 127.0.0.1<?php foreach ($VAR->server->ipAddresses->all as $ipaddress): ?>
<?php echo ($ipaddress->isIpV6() ? '' : ' ' . $ipaddress->escapedAddress) ?>
<?php endforeach; ?>

</IfModule>

<IfModule mod_rpaf-2.0.c>
        RPAFenable On
        RPAFsethostname On
        RPAFproxy_ips 127.0.0.1<?php foreach ($VAR->server->ipAddresses->all as $ipaddress): ?>
<?php echo ($ipaddress->isIpV6() ? '' : ' ' . $ipaddress->escapedAddress) ?>
<?php endforeach; ?>

        RPAFheader X-Forwarded-For
</IfModule>

<?php
echo $VAR->includeTemplate('server/nameVirtualHost.php', array(
    'ssl' => false,
));

echo $VAR->includeTemplate('server/nameVirtualHost.php', array(
    'ssl' => true,
));
?>

ServerName "<?php echo $VAR->server->fullHostName ?>"
<?php if ($VAR->server->admin->email): ?>
ServerAdmin "<?php echo $VAR->server->admin->email ?>"
<?php endif; ?>

DocumentRoot "<?php echo $VAR->server->webserver->httpDir ?>"

<IfModule mod_logio.c>
    LogFormat "<?php echo $VAR->server->webserver->apache->pipelogEnabled ? '%v@@%p@@' : ''?>%h %l %u %t \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\"" plesklog
</IfModule>
<IfModule !mod_logio.c>
    LogFormat "<?php echo $VAR->server->webserver->apache->pipelogEnabled ? '%v@@%p@@' : ''?>%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" plesklog
</IfModule>
<?php if ($VAR->server->webserver->apache->pipelogEnabled): ?>
CustomLog  "|<?php echo $VAR->server->productRootDir ?>/admin/sbin/pipelog <?php echo $VAR->server->webserver->httpsPort ?>" plesklog
<?php endif; ?>

<?php echo $VAR->includeTemplate('server/PCI_compliance.php') ?>

<Directory "<?php echo $VAR->server->webserver->vhostsDir ?>">
    AllowOverride "<?php echo $VAR->server->webserver->apache->allowOverrideDefault ?>"
    Options SymLinksIfOwnerMatch
    Order allow,deny
    Allow from all

<?php echo $VAR->includeTemplate('service/php.php', array(
    'enabled' => false,
)) ?>

</Directory>

<Directory "<?php echo $VAR->server->mailman->rootDir ?>">
    AllowOverride All
    Options SymLinksIfOwnerMatch
    Order allow,deny
    Allow from all
    <IfModule <?php echo $VAR->server->webserver->apache->php4ModuleName ?>>
        php_admin_flag engine off
    </IfModule>
    <IfModule mod_php5.c>
        php_admin_flag engine off
    </IfModule>
</Directory>

<IfModule mod_headers.c>
    Header add X-Powered-By PleskLin
</IfModule>

<?php echo $VAR->includeTemplate('server/tomcat.php') ?>

Include "<?php echo $VAR->domainsIpDefaultBootstrap ?>*"

<?php echo $VAR->includeTemplate('server/vhosts.php', array(
    'ssl' => false,
    'ipLimit' => $VAR->server->webserver->apache->vhostIpCapacity,
)) ?>
<?php echo $VAR->includeTemplate('server/vhosts.php', array(
    'ssl' => true,
    'ipLimit' => 1,
)) ?>

<?php echo $VAR->includeTemplate('server/mailman.php') ?>

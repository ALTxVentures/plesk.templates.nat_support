<?php
/**
 * @var Template_VariableAccessor $VAR
 * @var array $OPT
 */
?>

<?php include('/usr/local/psa/admin/conf/templates/custom/lib/nat_resolve.inc.php');?>

<?php if (nat_resolve($OPT['ipAddress']->escapedAddress) != null ): ?>

server {
    listen <?php echo nat_resolve($OPT['ipAddress']->escapedAddress) . ':' . $OPT['frontendPort'] . ($OPT['defaultIp'] ? ' default_server' : '') ?>;

    server_name <?php echo $VAR->domain->asciiName ?>;
<?php if ($VAR->domain->isWildcard): ?>
    server_name <?php echo $VAR->domain->wildcardName ?>;
<?php else: ?>
    server_name www.<?php echo $VAR->domain->asciiName ?>;
<?php endif ?>
<?php if (!$VAR->domain->isWildcard): ?>
<?php   if ($OPT['ipAddress']->isIpV6()): ?>
    server_name ipv6.<?php echo $VAR->domain->asciiName ?>;
<?php   else: ?>
    server_name ipv4.<?php echo $VAR->domain->asciiName ?>;
<?php   endif ?>
<?php endif ?>

<?php foreach ($VAR->domain->webAliases as $alias): ?>
    server_name <?php echo  $alias->asciiName ?>;
    server_name www.<?php echo $alias->asciiName ?>;
    <?php if ($OPT['ipAddress']->isIpV6()): ?>
    server_name ipv6.<?php echo $alias->asciiName ?>;
    <?php else: ?>
    server_name ipv4.<?php echo $alias->asciiName ?>;
    <?php endif ?>
<?php endforeach ?>

    location / { # IPv6 isn't supported in proxy_pass yet.
        proxy_pass http://<?php echo ($OPT['ipAddress']->isIpV6() ? '127.0.0.1': nat_resolve($OPT['ipAddress']->escapedAddress)) ?>:<?php echo $OPT['backendPort'] ?>;
        proxy_set_header Host 			 $host;
        proxy_set_header X-Real-IP 		 $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        access_log off;
    }
}

<?php endif; ?>

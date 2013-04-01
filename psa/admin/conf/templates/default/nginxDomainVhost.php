#ATTENTION!
#
#DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY,
#SO ALL YOUR CHANGES WILL BE LOST THE NEXT TIME THE FILE IS GENERATED.
<?php if (!$VAR->domain->enabled): ?>
# Domain suspended
<?php return ?>
<?php endif ?>

<?php if ($VAR->domain->physicalHosting->ssl): ?>
<?php foreach ($VAR->domain->physicalHosting->ipAddresses as $ipAddress): ?>

<?php echo $VAR->includeTemplate('domain/nginxDomainVirtualHost.php',
    array(
        'ssl' => true,
        'frontendPort' => $VAR->server->nginx->httpsPort,
        'backendPort' => $VAR->server->webserver->httpsPort,
        'documentRoot' => $VAR->domain->physicalHosting->httpsDir,
        'ipAddress' => $ipAddress,
	'defaultIp' => $ipAddress->defaultDomainId == $VAR->domain->id ? true : false, 
    )) ?>

<?php endforeach ?>
<?php endif ?>

<?php foreach ($VAR->domain->physicalHosting->ipAddresses as $ipAddress): ?>

<?php echo $VAR->includeTemplate('domain/nginxDomainVirtualHost.php',
    array(
        'ssl' => false,
        'frontendPort' => $VAR->server->nginx->httpPort,
        'backendPort' => $VAR->server->webserver->httpPort,
        'documentRoot' => $VAR->domain->physicalHosting->httpDir,
        'ipAddress' => $ipAddress,
	'defaultIp' => $ipAddress->defaultDomainId == $VAR->domain->id ? true : false, 
    )) ?>

<?php endforeach ?>

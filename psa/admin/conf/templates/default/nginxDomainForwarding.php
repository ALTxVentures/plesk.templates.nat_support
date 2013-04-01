#ATTENTION!
#
#DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY,
#SO ALL YOUR CHANGES WILL BE LOST THE NEXT TIME THE FILE IS GENERATED.
<?php if (!$VAR->domain->enabled): ?>

# Domain suspended

<?php return ?>
<?php endif ?>

<?php foreach ($VAR->domain->forwarding->ipAddresses as $ipAddress): ?>
<?php if ($VAR->domain->hasStandardForwarding || $VAR->domain->hasFrameForwarding): ?>

    <?php echo $VAR->includeTemplate('domain/nginxForwarding.php', array(
            'ipAddress' => $ipAddress,
            'frontendPort' => $VAR->server->nginx->httpPort,
            'backendPort' => $VAR->server->webserver->httpPort,
            'defaultIp' => $ipAddress->defaultDomainId == $VAR->domain->id ? true : false,
        )) ?>

<?php endif ?>

<?php endforeach ?>

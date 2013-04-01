#ATTENTION!
#
#DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY,
#SO ALL YOUR CHANGES WILL BE LOST THE NEXT TIME THE FILE IS GENERATED.
#
#IF YOU REQUIRE TO APPLY CUSTOM MODIFICATIONS, PERFORM THEM IN THE  FOLLOWING FILES:
<?php

if (!$VAR->domain->enabled) {
    echo "# Domain suspended\n";
    return;
}

echo '#' . $VAR->domain->physicalHosting->customConfigFile . "\n";

if ($VAR->domain->physicalHosting->ssl) {
    echo '#' . $VAR->domain->physicalHosting->customSslConfigFile . "\n";
}

foreach ($VAR->domain->physicalHosting->subdomains as $subdomain) {
    echo '#' . $subdomain->customConfigFile . "\n";
    if ($subdomain->ssl) {
        echo '#' . $subdomain->customSslConfigFile . "\n";
    }
}

if ($VAR->domain->physicalHosting->ssl) {
    foreach ($VAR->domain->physicalHosting->ipAddresses as $ipAddress) {
        if ($ipAddress->defaultDomainId == $VAR->domain->id) {
            echo $VAR->includeTemplate('domain/domainVirtualHost.php', array(
                'ssl' => true,
                'ipAddress' => $ipAddress,
            ));
        }
    }
}

foreach ($VAR->domain->physicalHosting->ipAddresses as $ipAddress) {
    if ($ipAddress->defaultDomainId == $VAR->domain->id) {
        echo $VAR->includeTemplate('domain/domainVirtualHost.php', array(
            'ssl' => false,
            'ipAddress' => $ipAddress,
        ));
    }
}

foreach ($VAR->domain->physicalHosting->subdomains as $subdomain) {
    foreach ($VAR->domain->physicalHosting->ipAddresses as $ipAddress) {
        if ($ipAddress->defaultDomainId == $VAR->domain->id) {
            if ($subdomain->ssl) {
                echo $VAR->includeTemplate('domain/subDomainVirtualHost.php', array(
                    'ssl' => true,
                    'ipAddress' => $ipAddress,
                ), array(
                    'subDomainId' => $subdomain->id,
                ));
            }

            echo $VAR->includeTemplate('domain/subDomainVirtualHost.php', array(
                'ssl' => false,
                'ipAddress' => $ipAddress,
            ), array(
                'subDomainId' => $subdomain->id,
            ));
        }
    }
}

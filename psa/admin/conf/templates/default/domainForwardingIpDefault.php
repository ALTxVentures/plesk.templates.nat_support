#ATTENTION!
#
#DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY,
#SO ALL YOUR CHANGES WILL BE LOST THE NEXT TIME THE FILE IS GENERATED.
<?php
if (!$VAR->domain->enabled) {
    echo "# Domain suspended\n";
} else {
    foreach ($VAR->domain->forwarding->ipAddresses as $ipAddress) {
        if ($ipAddress->defaultDomainId == $VAR->domain->id) {
            if ($VAR->domain->hasStandardForwarding) {
                echo $VAR->includeTemplate('domain/standardForwarding.php', array(
                    'ssl' => false,
                    'serverAdmin' => $VAR->domain->email ? $VAR->domain->email : $VAR->domain->client->email,
                    'ipAddress' => $ipAddress,
                ));
            } elseif ($VAR->domain->hasFrameForwarding) {
                echo $VAR->includeTemplate('domain/frameForwarding.php', array(
                    'ssl' => false,
                    'serverAdmin' => $VAR->domain->email ? $VAR->domain->email : $VAR->domain->client->email,
                    'ipAddress' => $ipAddress,
                ));
            }
        }
    }
}

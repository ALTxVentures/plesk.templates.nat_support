<?php require_once('/usr/local/psa/admin/conf/templates/custom/lib/nat_resolve.inc.php');?>
<IfModule <?php echo $OPT['mod'] ?>>
    RPAFproxy_ips <?php 
    	foreach($VAR->server->ipAddresses->all as $ipAddress): 
            $ip['public'] = $ipAddress->escapedAddress;
            $ip['private'] = nat_resolve($ipAddress->escapedAddress);

            if ( $ip['private']!= null ):
                foreach ($ip AS $ip_address): 
                	echo ' ' . $ip_address;
            	endforeach;
            endif;
		endforeach; ?>

</IfModule>

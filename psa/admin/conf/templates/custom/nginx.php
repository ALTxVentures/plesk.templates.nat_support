<?php /** @var Template_VariableAccessor $VAR */ ?>

<?php include('/usr/local/psa/admin/conf/templates/custom/lib/nat_resolve.inc.php');?>

server {
<?php foreach ($VAR->server->ipAddresses->all as $ipAddress): ?>
 <?php 
            $ip['public'] = $ipAddress->escapedAddress;
            $ip['private'] = nat_resolve($ipAddress->escapedAddress);

            if ( $ip['private']!= null ):
                foreach ($ip AS $ipaddress):
        ?>
    listen <?php echo $ipaddress.":{$VAR->server->nginx->httpPort}" ?> <?php if ($ipAddress->isIpV6) echo 'ipv6only=on'; else $hasIpV4=true; ?>;
    <?php endforeach; ?>
	<?php endif; ?>
<?php endforeach; ?>
    <?php if (!$hasIpV4) echo 'listen 127.0.0.1:' . $VAR->server->nginx->httpPort . ' ;'; ?>

    location / {
        proxy_pass http://127.0.0.1:<?php echo $VAR->server->webserver->httpPort ?>;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
}

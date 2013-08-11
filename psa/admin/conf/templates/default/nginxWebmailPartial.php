<?php /** @var Template_VariableAccessor $VAR */ ?>
<?php foreach ($VAR->server->ipAddresses->all as $ipAddress): ?>
server {
    listen <?php echo $ipAddress->escapedAddress . ':' . $OPT['frontendPort'] . ($OPT['ssl'] ? ' ssl' : '') ?>;
    server_name webmail.* roundcube.webmail.* horde.webmail.* atmail.webmail.*;

<?php if ($OPT['ssl']): ?>
<?php $sslCertificate = $ipAddress->sslCertificate; ?>
<?php   if ($sslCertificate->ce): ?>
    ssl_certificate             <?php echo $sslCertificate->ceFilePath ?>;
    ssl_certificate_key         <?php echo $sslCertificate->ceFilePath ?>;
<?php       if ($sslCertificate->ca): ?>
    ssl_client_certificate      <?php echo $sslCertificate->caFilePath ?>;
<?php       endif ?>
    ssl_session_timeout         5m;

    ssl_protocols               SSLv2 SSLv3 TLSv1;
    ssl_ciphers                 HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers   on;
<?php   endif ?>
<?php endif ?>

    client_max_body_size 128m;

    location / {
<?php if ($OPT['ssl']): ?>
        proxy_pass https://<?php echo $ipAddress->proxyEscapedAddress . ':' . $OPT['backendPort'] ?>;
<?php else: ?>
        proxy_pass http://<?php echo $ipAddress->proxyEscapedAddress . ':' . $OPT['backendPort'] ?>;
<?php endif ?>
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
}

<?php endforeach; ?>
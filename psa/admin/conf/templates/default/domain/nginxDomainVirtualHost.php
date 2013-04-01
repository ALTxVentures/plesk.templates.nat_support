<?php
/**
 * @var Template_VariableAccessor $VAR
 * @var array $OPT
 */
?>
server {
    listen <?php echo $OPT['ipAddress']->escapedAddress . ':' . $OPT['frontendPort'] . ($OPT['defaultIp'] ? ' default_server' : '') . ($OPT['ssl'] ? ' ssl' : '') ?>;

    server_name <?php echo $VAR->domain->asciiName ?>;
<?php if ($VAR->domain->isWildcard): ?>
    server_name <?php echo $VAR->domain->wildcardName ?>;
<?php else: ?>
    server_name www.<?php echo $VAR->domain->asciiName ?>;
<?php   if ($OPT['ipAddress']->isIpV6()): ?>
    server_name ipv6.<?php echo $VAR->domain->asciiName ?>;
<?php   else: ?>
    server_name ipv4.<?php echo $VAR->domain->asciiName ?>;
<?php   endif ?>
<?php endif ?>
<?php if ($VAR->domain->webAliases): ?>
    # aliases
<?php   foreach ($VAR->domain->webAliases as $alias): ?>
    server_name <?php echo $alias->asciiName ?>;
    server_name www.<?php echo $alias->asciiName ?>;
<?php   endforeach ?>
<?php endif ?>
<?php if ($VAR->domain->previewDomainName): ?>
    # preview domain name
    server_name "<?php echo $VAR->domain->previewDomainName ?>";
<?php endif ?>

<?php if ($OPT['ssl']): ?>
<?php $sslCertificate = $VAR->server->sni && $VAR->domain->physicalHosting->sslCertificate ?
    $VAR->domain->physicalHosting->sslCertificate :
    $OPT['ipAddress']->sslCertificate; ?>
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

    location / { # IPv6 isn't supported in proxy_pass yet.
<?php if ($OPT['ssl']): ?>
        proxy_pass https://<?php echo ($OPT['ipAddress']->isIpV6() ? '127.0.0.1': $OPT['ipAddress']->escapedAddress) ?>:<?php echo $OPT['backendPort'] ?>;
<?php else: ?>
        proxy_pass http://<?php echo ($OPT['ipAddress']->isIpV6() ? '127.0.0.1': $OPT['ipAddress']->escapedAddress) ?>:<?php echo $OPT['backendPort'] ?>;
<?php endif ?>

        proxy_set_header Host             $host;
        proxy_set_header X-Real-IP        $remote_addr;
        proxy_set_header X-Forwarded-For  $proxy_add_x_forwarded_for;
        proxy_set_header X-Accel-Internal /internal-nginx-static-location;
        access_log off;
    }

    location /internal-nginx-static-location/ {
        alias      <?php echo $OPT['documentRoot'] ?>/;
        access_log <?php echo $VAR->domain->physicalHosting->logsDir . '/' . ($OPT['ssl'] ? 'proxy_access_ssl_log' : 'proxy_access_log') ?>;
        add_header X-Powered-By PleskLin;
        internal;
    }
}

server {
    listen <?php echo $OPT['ipAddress']->escapedAddress . ':' . $OPT['frontendPort'] . ($OPT['ssl'] ? ' ssl' : '') ?>;
    server_name webmail.<?php echo $VAR->domain->asciiName ?>;
<?php if ($VAR->domain->webAliases): ?>
    # aliases
<?php   foreach ($VAR->domain->webAliases as $alias): ?>
    server_name webmail.<?php echo $alias->asciiName ?>;
<?php   endforeach ?>
<?php endif ?>

<?php if ($OPT['ssl']): ?>
<?php $sslCertificate = $VAR->server->sni && $VAR->domain->physicalHosting->sslCertificate ?
    $VAR->domain->physicalHosting->sslCertificate :
    $OPT['ipAddress']->sslCertificate; ?>
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

    location / { # IPv6 isn't supported in proxy_pass yet.
<?php if ($OPT['ssl']): ?>
        proxy_pass https://<?php echo ($OPT['ipAddress']->isIpV6() ? '127.0.0.1': $OPT['ipAddress']->escapedAddress) ?>:<?php echo $OPT['backendPort'] ?>;
<?php else: ?>
        proxy_pass http://<?php echo ($OPT['ipAddress']->isIpV6() ? '127.0.0.1': $OPT['ipAddress']->escapedAddress) ?>:<?php echo $OPT['backendPort'] ?>;
<?php endif ?>

        proxy_set_header Host             $host;
        proxy_set_header X-Real-IP        $remote_addr;
        proxy_set_header X-Forwarded-For  $proxy_add_x_forwarded_for;
        access_log <?php echo $VAR->domain->physicalHosting->logsDir . '/' . ($OPT['ssl'] ? 'webmail_access_ssl_log' : 'webmail_access_log') ?>;
    }
}

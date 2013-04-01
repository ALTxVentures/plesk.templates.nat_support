<?php include('/usr/local/psa/admin/conf/templates/custom/lib/nat_resolve.inc.php');?>

<?php if (nat_resolve($OPT['ipAddress']->escapedAddress) != null ): ?>

<?php if ($OPT['ssl']): ?>
<IfModule mod_ssl.c>
<?php endif; ?>

<VirtualHost <?php echo nat_resolve($OPT['ipAddress']->escapedAddress) ?>:<?php echo $OPT['ssl'] ? $VAR->server->webserver->httpsPort : $VAR->server->webserver->httpPort ?>>
    ServerName "<?php echo $VAR->subDomain->asciiName ?>.<?php echo $VAR->domain->asciiName ?>"
    ServerAlias  "www.<?php echo $VAR->subDomain->asciiName ?>.<?php echo $VAR->domain->asciiName ?>"
    <?php if ($OPT['ipAddress']->isIpV6()): ?>
    ServerAlias  "ipv6.<?php echo $VAR->subDomain->asciiName ?>.<?php echo $VAR->domain->asciiName ?>"
    <?php else: ?>
    ServerAlias  "ipv4.<?php echo $VAR->subDomain->asciiName ?>.<?php echo $VAR->domain->asciiName ?>"
    <?php endif; ?>
	UseCanonicalName Off
<?php foreach ($VAR->domain->webAliases AS $alias): ?>
    ServerAlias  "<?php echo $VAR->subDomain->asciiName ?>.<?php echo $alias->asciiName ?>"
    ServerAlias  "www.<?php echo $VAR->subDomain->asciiName ?>.<?php echo $alias->asciiName ?>"
    <?php if ($OPT['ipAddress']->isIpV6()): ?>
    ServerAlias  "ipv6.<?php echo $VAR->subDomain->asciiName ?>.<?php echo $alias->asciiName ?>"
    <?php else: ?>
    ServerAlias  "ipv4.<?php echo $VAR->subDomain->asciiName ?>.<?php echo $alias->asciiName ?>"
    <?php endif; ?>
<?php endforeach; ?>

<?php if ($VAR->domain->previewDomainName): ?>
    ServerAlias "<?php echo $VAR->domain->previewDomainName ?>"
<?php endif; ?>

<IfModule mod_suexec.c>
    SuexecUserGroup "<?php echo $VAR->subDomain->login ?>" "<?php echo $VAR->server->webserver->clientGroup ?>"
</IfModule>

<?php if($VAR->domain->email || $VAR->domain->client->email): ?>
    ServerAdmin  "<?php echo $VAR->domain->email ? $VAR->domain->email : $VAR->domain->client->email ?>"
<?php endif; ?>

	DocumentRoot "<?php echo $OPT['ssl'] ? $VAR->subDomain->httpsDir : $VAR->subDomain->httpDir ?>"
<?php if (!$VAR->server->webserver->apache->pipelogEnabled): ?>
    CustomLog <?php echo $VAR->domain->physicalHosting->logsDir ?>/<?php echo $OPT['ssl'] ? 'access_ssl_log' : 'access_log' ?> plesklog
<?php endif; ?>
    ErrorLog "<?php echo $VAR->domain->physicalHosting->logsDir ?>/error_log"

<?php if ($VAR->subDomain->maintenanceMode): ?>
    RedirectMatch 503 ^/(?!error_docs/)
<?php endif; ?>

<?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

<?php if ($VAR->subDomain->cgi && !$VAR->subDomain->rootApplication): ?>
    ScriptAlias  /cgi-bin/ "<?php echo $VAR->subDomain->cgiBinDir ?>/"
<?php endif; ?>

<?php
if ($VAR->subDomain->miva) {
    echo $VAR->includeTemplate('service/miva.php', array(
        'dataDir' => $VAR->subDomain->mivaDataDir,
        'hostDir' => $OPT['ssl'] ? $VAR->subDomain->httpsDir : $VAR->subDomain->httpDir,
    ) + $OPT);
}
?>

<?php if ($OPT['ssl']): ?>
<?php $sslCertificate = $VAR->server->sni && $VAR->subDomain->sslCertificate ?
    $VAR->subDomain->sslCertificate :
    $OPT['ipAddress']->sslCertificate; ?>

<?php if ($sslCertificate->ce): ?>
    SSLEngine on
    SSLVerifyClient none
    SSLCertificateFile <?php echo $sslCertificate->ceFilePath ?>

<?php if ($sslCertificate->ca): ?>
    SSLCACertificateFile <?php echo $sslCertificate->caFilePath ?>
<?php endif; ?>
<?php endif; ?>
<?php else: ?>
    <IfModule mod_ssl.c>
        SSLEngine off
    </IfModule>
<?php endif;?>

    <Directory <?php echo $OPT['ssl'] ? $VAR->subDomain->httpsDir : $VAR->subDomain->httpDir ?>>

<?php
if ($VAR->subDomain->perl) {
    echo $VAR->includeTemplate('service/mod_perl.php');
}

if ($VAR->subDomain->asp) {
    echo $VAR->includeTemplate('service/asp.php');
}

if ($VAR->subDomain->coldfusion) {
    echo $VAR->includeTemplate('service/coldfusion.php');
}

if (
    !$VAR->subDomain->php ||
    !in_array($VAR->subDomain->phpHandlerType, array('cgi', 'fastcgi'))
) {
    echo $VAR->includeTemplate('service/php.php', array(
        'enabled' => $VAR->subDomain->php,
        'safe_mode' => $VAR->domain->physicalHosting->phpSafeMode,
        'dir' => $OPT['ssl'] ? $VAR->subDomain->httpsDir : $VAR->subDomain->httpDir,
    ));
}

if ($VAR->subDomain->python) {
    echo $VAR->includeTemplate('service/mod_python.php');
}

if ($VAR->subDomain->fastcgi) {
    echo $VAR->includeTemplate('service/mod_fastcgi.php');
}

if ($VAR->subDomain->php && 'cgi' == $VAR->subDomain->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_cgi.php');
}

if ($VAR->subDomain->php && 'fastcgi' == $VAR->subDomain->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_fastcgi.php');
}
?>

<?php if ($OPT['ssl']): ?>
        SSLRequireSSL
<?php endif; ?>

        Options <?php echo $VAR->subDomain->ssi ? '+' : '-' ?>Includes <?php echo $VAR->subDomain->cgi ? '+' : '-' ?>ExecCGI

    </Directory>

<?php
if ($VAR->domain->physicalHosting->errordocs) {
    echo $VAR->includeTemplate('domain/service/errordocs.php');
}

echo $VAR->includeTemplate('domain/service/bandWidth.php');
?>

<?php if (is_dir($OPT['ssl'] ? $VAR->subDomain->siteAppsSslConfigDir : $VAR->subDomain->siteAppsConfigDir)): ?>
    Include "<?php echo $OPT['ssl'] ? $VAR->subDomain->siteAppsSslConfigDir : $VAR->subDomain->siteAppsConfigDir ?>/*.conf"
<?php endif; ?>

<?php if (is_file($OPT['ssl'] ? $VAR->subDomain->customSslConfigFile : $VAR->subDomain->customConfigFile)): ?>
    Include "<?php echo $OPT['ssl'] ? $VAR->subDomain->customSslConfigFile : $VAR->subDomain->customConfigFile ?>*"
<?php endif; ?>

</VirtualHost>

<?php if ($OPT['ssl']): ?>
</IfModule>
<?php endif; ?>


<?php endif; ?>
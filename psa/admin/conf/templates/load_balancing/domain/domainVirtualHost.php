<?php if ($OPT['ssl']): ?>
<IfModule mod_ssl.c>
<?php endif; ?>

<VirtualHost *:<?php echo $OPT['ssl'] ? $VAR->server->webserver->httpsPort : $VAR->server->webserver->httpPort ?>>
    ServerName "<?php echo $VAR->domain->asciiName ?>"
    <?php if ($VAR->domain->isWildcard): ?>
    ServerAlias  "<?php echo $VAR->domain->wildcardName ?>"
    <?php else: ?>
    ServerAlias  "www.<?php echo $VAR->domain->asciiName ?>"
    <?php endif; ?>
	UseCanonicalName Off
<?php foreach ($VAR->domain->webAliases AS $alias): ?>
    ServerAlias  "<?php echo $alias->asciiName ?>"
    ServerAlias  "www.<?php echo $alias->asciiName ?>"
<?php endforeach; ?>

<IfModule mod_suexec.c>
    SuexecUserGroup "<?php echo $VAR->domain->physicalHosting->login ?>" "<?php echo $VAR->server->webserver->clientGroup ?>"
</IfModule>

<?php if($VAR->domain->email || $VAR->domain->client->email): ?>
    ServerAdmin  "<?php echo $VAR->domain->email ? $VAR->domain->email : $VAR->domain->client->email ?>"
<?php endif; ?>

	DocumentRoot "<?php echo $OPT['ssl'] ? $VAR->domain->physicalHosting->httpsDir : $VAR->domain->physicalHosting->httpDir ?>"
<?php if (!$VAR->server->webserver->apache->pipelogEnabled): ?>
    CustomLog <?php echo $VAR->domain->physicalHosting->logsDir ?>/<?php echo $OPT['ssl'] ? 'access_ssl_log' : 'access_log' ?> plesklog
<?php endif; ?>
    ErrorLog  "<?php echo $VAR->domain->physicalHosting->logsDir ?>/error_log"

<?php if ($VAR->domain->physicalHosting->maintenanceMode): ?>
    RedirectMatch 503 ^/(?!error_docs/)
<?php endif; ?>

<?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

<IfModule mod_userdir.c>
    UserDir "<?php echo $VAR->domain->physicalHosting->webUsersDir ?>"
</IfModule>

<?php if ($VAR->domain->physicalHosting->cgi && !$VAR->domain->physicalHosting->rootApplication): ?>
    ScriptAlias  "/cgi-bin/" "<?php echo $VAR->domain->physicalHosting->cgiBinDir ?>/"
<?php endif; ?>

<?php
if ($VAR->domain->physicalHosting->miva) {
    echo $VAR->includeTemplate('service/miva.php', array(
        'dataDir' => $VAR->domain->physicalHosting->mivaDataDir,
        'hostDir' => $OPT['ssl'] ? $VAR->domain->physicalHosting->httpsDir : $VAR->domain->physicalHosting->httpDir,
    ) + $OPT);

    echo "\nSetEnv MvCONFIG_DIR_USER ./\n";
}
?>

<?php if ($VAR->domain->physicalHosting->hasWebstat):?>

<?php if ($OPT['ssl'] || !$VAR->domain->physicalHosting->ssl): ?>
    Alias  "/plesk-stat" "<?php echo $VAR->domain->physicalHosting->statisticsDir ?>"
    <Location  /plesk-stat/>
        Options +Indexes
    </Location>
    <Location  /plesk-stat/logs/>
        Require valid-user
    </Location>
    Alias  /webstat <?php echo $VAR->domain->physicalHosting->statisticsDir ?>/webstat
    Alias  /webstat-ssl <?php echo $VAR->domain->physicalHosting->statisticsDir ?>/webstat-ssl
    Alias  /ftpstat <?php echo $VAR->domain->physicalHosting->statisticsDir ?>/ftpstat
    Alias  /anon_ftpstat <?php echo $VAR->domain->physicalHosting->statisticsDir ?>/anon_ftpstat
    Alias  /awstats-icon <?php echo $VAR->server->awstats->iconsDir ?>


<?php else: ?>
    Redirect permanent /plesk-stat https://<?php echo $VAR->domain->asciiName ?>/plesk-stat
    Redirect permanent /webstat https://<?php echo $VAR->domain->asciiName ?>/webstat
    Redirect permanent /webstat-ssl https://<?php echo $VAR->domain->asciiName ?>/webstat-ssl
    Redirect permanent /ftpstat https://<?php echo $VAR->domain->asciiName ?>/ftpstat
    Redirect permanent /anon_ftpstat https://<?php echo $VAR->domain->asciiName ?>/anon_ftpstat
    Redirect permanent /awstats-icon https://<?php echo $VAR->domain->asciiName ?>/awstats-icon
<?php endif; ?>

<?php endif; ?>

<?php if ($OPT['ssl']): ?>
<?php if ($VAR->server->defaultSslCertificate->ce): ?>
    SSLEngine on
    SSLVerifyClient none
    SSLCertificateFile "<?php echo $VAR->server->defaultSslCertificate->ceFilePath ?>"

<?php if ($VAR->server->defaultSslCertificate->ca): ?>
    SSLCACertificateFile "<?php echo $VAR->server->defaultSslCertificate->caFilePath ?>"
<?php endif; ?>
<?php endif; ?>
<?php else: ?>
    <IfModule mod_ssl.c>
        SSLEngine off
    </IfModule>
<?php endif; ?>

    <Directory <?php echo $OPT['ssl'] ? $VAR->domain->physicalHosting->httpsDir : $VAR->domain->physicalHosting->httpDir ?>>

<?php
if ($VAR->domain->physicalHosting->perl) {
    echo $VAR->includeTemplate('service/mod_perl.php');
}

if ($VAR->domain->physicalHosting->asp) {
    echo $VAR->includeTemplate('service/asp.php');
}

if (
    !$VAR->domain->physicalHosting->php ||
    !in_array($VAR->domain->physicalHosting->phpHandlerType, array('cgi', 'fastcgi'))
) {
    echo $VAR->includeTemplate('service/php.php', array(
        'enabled' => $VAR->domain->physicalHosting->php,
        'safe_mode' => $VAR->domain->physicalHosting->phpSafeMode,
        'dir' => $OPT['ssl'] ? $VAR->domain->physicalHosting->httpsDir : $VAR->domain->physicalHosting->httpDir,
    ));
}

if ($VAR->domain->physicalHosting->python) {
    echo $VAR->includeTemplate('service/mod_python.php');
}

if ($VAR->domain->physicalHosting->fastcgi) {
    echo $VAR->includeTemplate('service/mod_fastcgi.php');
}

if ($VAR->domain->physicalHosting->php && 'cgi' == $VAR->domain->physicalHosting->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_cgi.php');
}

if ($VAR->domain->physicalHosting->php && 'fastcgi' == $VAR->domain->physicalHosting->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_fastcgi.php');
}
?>

<?php if ($OPT['ssl']): ?>
        SSLRequireSSL
<?php endif; ?>

        Options <?php echo $VAR->domain->physicalHosting->ssi ? '+' : '-' ?>Includes <?php echo $VAR->domain->physicalHosting->cgi ? '+' : '-' ?>ExecCGI

    </Directory>

<?php if ($VAR->domain->physicalHosting->webusersScriptingEnabled): ?>
<?php foreach ($VAR->domain->physicalHosting->webusers as $webuser): ?>
    <Directory <?php echo $webuser->dir ?>>
        Options <?php echo $VAR->domain->physicalHosting->ssi && $webuser->ssi ? '+' : '-' ?>Includes <?php echo $VAR->domain->physicalHosting->cgi && $webuser->cgi ? '+' : '-' ?>ExecCGI

<?php if ($VAR->domain->physicalHosting->cgi && $webuser->cgi): ?>
        AddHandler cgi-script .cgi
<?php endif; ?>

<?php
if ($VAR->domain->physicalHosting->perl && $webuser->perl) {
    echo $VAR->includeTemplate('service/mod_perl.php');
}

if ($VAR->domain->physicalHosting->asp && $webuser->asp) {
    echo $VAR->includeTemplate('service/asp.php');
}

if (
    !$VAR->domain->physicalHosting->php ||
    !in_array($VAR->domain->physicalHosting->phpHandlerType, array('cgi', 'fastcgi'))
) {
    echo $VAR->includeTemplate('service/php.php', array(
        'enabled' => $VAR->domain->physicalHosting->php && $webuser->php,
        'safe_mode' => $VAR->domain->physicalHosting->phpSafeMode,
        'dir' => $webuser->dir,
    ));
}

if ($VAR->domain->physicalHosting->php && $webuser->php && 'cgi' == $VAR->domain->physicalHosting->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_cgi.php');
}

if ($VAR->domain->physicalHosting->php && $webuser->php && 'fastcgi' == $VAR->domain->physicalHosting->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_fastcgi.php');
}

if ($VAR->domain->physicalHosting->python && $webuser->python) {
    echo $VAR->includeTemplate('service/mod_python.php');
}

if ($VAR->domain->physicalHosting->fastcgi && $webuser->fastcgi) {
    echo $VAR->includeTemplate('service/mod_fastcgi.php');
}
?>

    </Directory>
<?php endforeach; ?>

<?php else: ?>

    <Directory <?php echo $VAR->domain->physicalHosting->webUsersDir ?>>

<?php echo $VAR->includeTemplate('service/php.php', array(
    'enabled' => false,
    'safe_mode' => true,
    'dir' => $VAR->domain->physicalHosting->webUsersDir,
)); ?>

    </Directory>

<?php endif; ?>

<?php
echo $VAR->includeTemplate('domain/service/protectedDirectories.php', $OPT);

if ($VAR->domain->physicalHosting->errordocs) {
    echo $VAR->includeTemplate('domain/service/errordocs.php');
}

if ($VAR->domain->tomcat->enabled) {
    echo $VAR->includeTemplate('domain/service/tomcat.php');
}

if ($OPT['ssl'] ? $VAR->domain->physicalHosting->frontpageSsl : $VAR->domain->physicalHosting->frontpage) {
    echo $VAR->includeTemplate('domain/service/frontpageWorkaround.php', $OPT);
}

if ($VAR->domain->physicalHosting->coldfusion) {
    echo $VAR->includeTemplate('service/coldfusion.php');
}

echo $VAR->includeTemplate('domain/service/bandWidth.php');
?>

<?php if (is_dir($OPT['ssl'] ? $VAR->domain->physicalHosting->siteAppsSslConfigDir : $VAR->domain->physicalHosting->siteAppsConfigDir)): ?>
    Include "<?php echo $OPT['ssl'] ? $VAR->domain->physicalHosting->siteAppsSslConfigDir : $VAR->domain->physicalHosting->siteAppsConfigDir ?>/*.conf"
<?php endif; ?>

<?php if (is_file($OPT['ssl'] ? $VAR->domain->physicalHosting->customSslConfigFile : $VAR->domain->physicalHosting->customConfigFile)): ?>
    Include "<?php echo $OPT['ssl'] ? $VAR->domain->physicalHosting->customSslConfigFile : $VAR->domain->physicalHosting->customConfigFile ?>*"
<?php endif; ?>

</VirtualHost>

<?php if ($OPT['ssl']): ?>
</IfModule>
<?php endif; ?>

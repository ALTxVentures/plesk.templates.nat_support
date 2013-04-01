<?php
    $hordeDocroot = $VAR->server->webserver->horde->docroot;
    $domainsBootstrap = $VAR->domainsWebmailHordeBootstrap;
    $hordeConfD = $VAR->server->webserver->horde->confD;
    $hordeLogD = $VAR->server->webserver->horde->logD;
    $hordeDocD = $VAR->server->webserver->horde->docD;
    $pearDataD = $VAR->server->webserver->horde->dataD;
?>
<VirtualHost *:<?php echo $VAR->server->webserver->httpPort ?>>

	ServerName horde.webmail
	ServerAlias horde.webmail.*
        ServerAdmin "<?php echo $VAR->server->admin->email ?>"

        Include "<?php echo $domainsBootstrap ?>*"
	UseCanonicalName Off

	DocumentRoot "<?php echo $hordeDocroot ?>"
	Alias /horde/ "<?php echo $hordeDocroot ?>"
	Alias /imp/ "<?php echo $hordeDocroot ?>/imp/"

    <?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

	<Directory "<?php echo $hordeDocroot ?>">
        <IfModule <?php echo $VAR->server->webserver->apache->php4ModuleName ?>>
			php_admin_flag engine on
			php_admin_flag magic_quotes_gpc off
			php_admin_flag safe_mode off

			php_admin_value upload_tmp_dir "/tmp/"
			php_admin_value open_basedir "<?php
                echo "$hordeDocroot/:$hordeConfD/:/tmp/:/var/tmp/:$hordeLogD/:$hordeDocD/:$pearDataD/" ?>"

			php_admin_value include_path "<?php
                echo "$hordeDocroot:$hordeDocroot/lib:$pearDataD:." ?>"
		</IfModule>

		<IfModule mod_php5.c>
			php_admin_flag engine on
			php_admin_flag magic_quotes_gpc off
			php_admin_flag safe_mode off

			php_admin_value upload_tmp_dir "/tmp/"
			php_admin_value open_basedir "<?php
                echo "$hordeDocroot/:$hordeConfD/:/tmp/:/var/tmp/:$hordeConfD/:$hordeDocD/:$pearDataD/" ?>"
			php_admin_value include_path "<?php
                echo "$hordeDocroot:$hordeDocroot/lib:$pearDataD:." ?>"
		</IfModule>

		Order allow,deny
		Allow from all
	</Directory>
</VirtualHost>

<?php
    /** @var Template_Variable_Abstract $VAR */
    $VAR->getServiceNode()->capability()->web()->apache()
        ->mkdir($OPT['dataDir']);
?>
ScriptAlias  /mivavm <?php echo $VAR->server->miva->binDir ?>/mivavm
SetEnv MvCONFIG_DIR_MIVA "<?php echo $OPT['hostDir'] ?>"
SetEnv MvCONFIG_DIR_DATA "<?php echo $OPT['dataDir'] ?>"
SetEnv MvCONFIG_DIR_CA <?php echo $VAR->server->miva->shareDir ?>/certs
SetEnv MvCONFIG_LIBRARY <?php echo $VAR->server->miva->libDir ?>/config/env.so
SetEnv MvCONFIG_DIR_BUILTIN <?php echo $VAR->server->miva->libDir ?>/builtins
SetEnv MvCONFIG_DATABASE_MIVASQL <?php echo $VAR->server->miva->libDir ?>/databases/mivasql.so
SetEnv MvCONFIG_SSL_OPENSSL "<?php echo $VAR->server->getSslLibraryPath ?>"
SetEnv MvCONFIG_SSL_CRYPTO "<?php echo $VAR->server->getCryptoLibraryPath ?>"
SetEnv MvCONFIG_COMMERCE_AuthorizeNet <?php echo $VAR->server->miva->libDir ?>/commerce/authnet.so
SetEnv MvCONFIG_COMMERCE_CyberCash <?php echo $VAR->server->miva->libDir ?>/commerce/cybercash.so
SetEnv MvCONFIG_COMMERCE_LinkPoint <?php echo $VAR->server->miva->libDir ?>/commerce/linkpoint.so
SetEnv MvCONFIG_COMMERCE_ICS2 <?php echo $VAR->server->miva->libDir ?>/commerce/ics2.so
SetEnv MvCONFIG_FLAGS_SECURITY 23

AddType application/x-miva-compiled .mvc
Action application/x-miva-compiled /mivavm

<Directory <?php echo $VAR->server->miva->binDir ?>>
    <Files mivavm>
	   order allow,deny
	   Allow from all
    </Files>
</Directory>

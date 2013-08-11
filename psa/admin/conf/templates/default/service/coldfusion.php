<IfModule <?php echo $VAR->server->webserver->apache->coldfusionModuleName ?>>
    JRunConfig Verbose false
    JRunConfig Apialloc false
    JRunConfig Ignoresuffixmap false
    JRunConfig Serverstore "<?php echo $VAR->server->coldfusion->serverStorePath ?>"
    JRunConfig Bootstrap "127.0.0.1:<?php echo $VAR->server->coldfusion->port ?>"
    AddHandler jrun-handler .jsp .jws .cfm .cfml .cfc
</IfModule>

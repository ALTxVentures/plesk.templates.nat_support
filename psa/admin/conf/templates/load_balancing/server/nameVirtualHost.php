NameVirtualHost *:<?php
    echo ($OPT['ssl']
        ? $VAR->server->webserver->httpsPort
        : $VAR->server->webserver->httpPort) . "\n" ?>

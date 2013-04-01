<?php if (!$VAR->server->webserver->apache->traceEnableCompliance): ?>
        TraceEnable off
<?php endif; ?>

ServerTokens ProductOnly

SSLProtocol -ALL +SSLv3 +TLSv1
SSLCipherSuite ALL:!aNULL:!ADH:!eNULL:!LOW:!EXP:RC4+RSA:+HIGH:+MEDIUM

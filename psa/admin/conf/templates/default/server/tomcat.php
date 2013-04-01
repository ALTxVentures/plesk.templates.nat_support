<IfModule mod_jk.c>
   JkWorkersFile "<?php echo $VAR->server->tomcat->workersFile ?>"
   <?php
    $writer = new Template_Writer_Apache($VAR->getServiceNode(), $VAR->server->tomcat->workersFile);
    $writer->write(
        "worker.list={$VAR->server->tomcat->workerName}\n" .
        "worker.{$VAR->server->tomcat->workerName}.port={$VAR->server->tomcat->warpPort}\n" .
        "worker.{$VAR->server->tomcat->workerName}.host=localhost\n" .
        "worker.{$VAR->server->tomcat->workerName}.type=ajp13\n"
    );
   ?>
   JkLogFile <?php echo $VAR->server->webserver->httpLogsDir ?>/mod_jk.log
   JkLogLevel info
</IfModule>

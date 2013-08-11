<?php foreach(($OPT['ssl'] ? $VAR->domain->protectedDirectories->sslDirectories : $VAR->domain->protectedDirectories->nonSslDirectories) as $directory): ?>
    location ~ ^/<?php echo $directory['relativePath']; ?>/ {
<?php echo $VAR->includeTemplate('domain/service/proxy.php', $OPT); ?>
    }
<?php endforeach; ?>
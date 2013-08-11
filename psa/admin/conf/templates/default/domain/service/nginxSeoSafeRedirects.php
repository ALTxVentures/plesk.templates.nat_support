<?php if ($VAR->domain->isSeoRedirectToLanding) : ?>
    if ($host ~* ^www.<?php echo $VAR->domain->asciiName ?>$) {
        rewrite ^(.*)$ <?php echo $OPT['ssl'] ? 'https' : 'http'; ?>://<?php echo $VAR->domain->asciiName ?>$1 permanent;
    }
<?php elseif ($VAR->domain->isSeoRedirectToWww): ?>
    if ($host ~* ^<?php echo $VAR->domain->asciiName ?>$) {
        rewrite ^(.*)$ <?php echo $OPT['ssl'] ? 'https' : 'http'; ?>://www.<?php echo $VAR->domain->asciiName ?>$1 permanent;
    }
<?php endif; ?>
<?php if ($VAR->domain->isAliasRedirected): ?>
<?php     foreach ($VAR->domain->webAliases AS $alias): ?>
<?php         if ($alias->isSeoRedirect) : ?>
    if ($host ~* ^<?php echo $alias->asciiName ?>$) {
        rewrite ^(.*)$ <?php echo $OPT['ssl'] ? 'https' : 'http'; ?>://<?php echo $VAR->domain->targetName; ?>$1 permanent;
    }
    if ($host ~* ^www.<?php echo $alias->asciiName ?>$) {
        rewrite ^(.*)$ <?php echo $OPT['ssl'] ? 'https' : 'http'; ?>://<?php echo $VAR->domain->targetName; ?>$1 permanent;
    }
<?php         endif; ?>
<?php     endforeach; ?>
<?php endif; ?>
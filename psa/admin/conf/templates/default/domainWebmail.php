#ATTENTION!
#
#DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY,
#SO ALL YOUR CHANGES WILL BE LOST THE NEXT TIME THE FILE IS GENERATED.

<?php
if (!$VAR->domain->enabled) {
    echo "# Domain suspended\n";
    return;
}
?>
ServerAlias "webmail.<?php echo $VAR->domain->asciiName ?>"
<?php foreach ($VAR->domain->mailAliases AS $alias): ?>
    ServerAlias  "webmail.<?php echo $alias->asciiName ?>"
<?php endforeach; ?>

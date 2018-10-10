<?php

require_once 'class/mpConfig.php';

$sec = new mpSecurity();
if( !$sec->isAuth() )
{
    $sec->redirectLoginPage();
}

$template = new mpTemplate("lojas");
$template->addDefaultJS();
$template->addDefaultCSS();

$template->pageHeader();
$template->pageToolset(5,"LOJAS");

?>
<div class="container">
</div>

<?php

$template->pageFooter();

?>
<?php

require_once 'class/mpConfig.php';

$sec = new mpSecurity();
if( !$sec->isAuth() )
{
    $sec->redirectLoginPage();
}

$template = new mpTemplate("modelos");
$template->addDefaultCSS();

$template->pageHeader();
$template->pageToolset();

?>
<div class="container">
<p>MODELOS</p>
</div>

<?php

$template->pageFooter();

?>
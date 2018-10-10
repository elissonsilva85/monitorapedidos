<?php

require_once 'class/mpConfig.php';

$sec = new mpSecurity();
if( !$sec->isAuth() )
{
    $sec->redirectLoginPage();
}

$sec->logoff();
$sec->redirectLoginPage();

?>
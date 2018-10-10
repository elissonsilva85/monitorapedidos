<?php

require_once 'class/mpConfig.php';

$sec = new mpSecurity();
if( !$sec->isAuth() )
{
    $sec->redirectLoginPage();
}

$shopId = $_GET['id'];

// Valida ShopId
$shop = new mpShop($shopId);
if( $shop->getId() == $shopId )
{
    $c = new mpConnect();
    $user = $sec->getLogedUser();

    // Entrega Efetuada
    if( isset($_GET['ee']) && $_GET['ee'] == 1 )
    {
        $query = "UPDATE trackings "
               . "   SET active = (active + 1) % 2 "
               . " WHERE was_delivered = 1 "
               . "   AND order_id IN (SELECT order_id "
               . "                      FROM orders "
               . "                     WHERE shop_id = {$shopId} "
               . "                       AND created_by = '{$user}');";
        $c->query($query);
    }
}

header("Location: home.php");

?>

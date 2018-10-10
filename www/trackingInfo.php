<?php

require_once 'class/mpConfig.php';
require_once 'phpQuery.php';

$sec = new mpSecurity();
if( !$sec->isAuth() )
{
    $sec->redirectLoginPage();
}

$template = new mpTemplate("getTrackingInfo");
$template->addDefaultCSS();

$template->pageHeader();
$template->pageToolset();

echo "<div class='container'>";

$c = new mpConnect();
$user = $sec->getLogedUser();
$shopId = $c->escape($_GET["shop"]);
$orderNo = $c->escape($_GET["order"]);

$query = "select o.order_id, s.name, s.link_site, s.link_order from orders o, shops s where o.shop_id = s.shop_id and o.created_by = '$user' and o.shop_id = '{$shopId}' and o.order_no = '{$orderNo}'";
$c->query($query);
if($c->nextRow())
{
    $orderId  = $c->getColumnValue("order_id");    
    $shopName = $c->getColumnValue("name");
    $shopLink = $c->getColumnValue("link_site");
    ?>
    <p>ORDEM: <strong><?php echo $orderNo ?></strong></p>
    <p>LOJA: <strong><a href="<?php echo $shopLink ?>" target="_blank"><?php echo $shopName ?></a></strong></p>
    <hr>
    <?php
    
    $query = "select tracking_id, tracking_no, last_update_date, last_update_info from trackings where order_id = '{$orderId}' and created_by = '$user' and active = 1 order by tracking_id asc;";
    $c->query($query);
    while($c->nextRow())
    {
        $trackingNo = $c->getColumnValue("tracking_no");
        $lastUpdate = $c->getColumnValue("last_update_date");
        $lastStatus = $c->getColumnValue("last_update_info");
    ?>
        <p>TRACKING: <strong><?php echo $trackingNo ?></strong></p>
        <p>ULTIMA ATUALIZAÇÃO EM: <strong><?php echo $lastUpdate ?></strong></p>
        <p>ULTIMO STATUS: <strong><?php echo $lastStatus ?></strong></p>
        <hr>
    <?php
    }
    ?>

    <?php
}
else
{
    echo "NENHUM INFORMAÇÃO LOCALIZADA PARA ESTA ORDEM";
}
echo "</div>";
$template->pageFooter();

?>
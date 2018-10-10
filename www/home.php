<?php

require_once 'class/mpConfig.php';

$sec = new mpSecurity();
if( !$sec->isAuth() )
{
    $sec->redirectLoginPage();
}

$template = new mpTemplate("home");
$template->addDefaultJS();
$template->addDefaultCSS();

$template->pageHeader();
$template->pageToolset(3,"PEDIDOS");

$entregaEfetuada = ( ( isset($_GET['ee']) && preg_match("(1|0)",$_GET['ee']) == 1 ) ? $_GET['ee'] : "0" );
$entregaEfetuadaLink = ( $entregaEfetuada ? "Oculta" : "Exibe" );
?>

<script>
function createTracking() {
  var div = $("#new_tracking");
  
  alert("new");
  
  div.dialog( "close" );
}
function newTracking() {

  var div = $("#new_tracking");
  div.html("AGUARDE ...")
     .load("newTracking.php")
     .dialog({
        title: "NOVO PEDIDO",
        width: 560,
        height: 450,
        top: 10,
        left: 10,
        modal: true,
        buttons: {
          "INCLUIR PEDIDO": createTracking,
          "CANCELAR": function() {
            div.dialog( "close" );
          }
        }
     });
    
}
$(function(){
  $("a.button").button();
});
</script>

<div id="new_tracking"></div>

<div class="container">
<table class="homeShopOrderList" cellspacing="0">
<thead>
<?php

$c = new mpConnect();
$user = $sec->getLogedUser();

$query = "select count(*) total_pedidos, sum( if(delivery_date_real is null,0,1) ) entregas_efetuadas "
       . "  from orders o "
       . " where o.created_by = '$user' ";
$c->query($query);
$c->nextRow();
$countTotalPedidos = $c->getColumnValue("total_pedidos"); 
$countEntregasEfetuadas = $c->getColumnValue("entregas_efetuadas"); 

if( $countTotalPedidos == 0 )
{
    ?>
    <tr>
        <td colspan="10">
        <a class="button" href="#" onclick="newTracking()">Clique aqui para incluir um pedido</a>
        </td>
    </tr>
    
    <tr>
        <td colspan="10">
        <p><strong>VOCÊ AINDA NÃO TEM NENHUM PEDIDO CONFIGURADO</strong></p>
        </td>
    </tr>
    <?php
}
else
{
    if( $countEntregasEfetuadas > 0 ):
    ?>
    <tr>
        <td colspan="10">
        <a class="button" href="#" onclick="newTracking()">Clique aqui para incluir um pedido</a>
        <a class="button" href="?ee=<?php echo ($entregaEfetuada + 1) % 2; ?>"><?php echo $entregaEfetuadaLink; ?> entregas efetuadas</a>
        </td>
    </tr>
    <?php
    endif;
    ?>
    <tr>
        <td colspan="10">

        <?php
        if( $countTotalPedidos == $countEntregasEfetuadas ):
        ?>
        <p><strong>TODOS OS SEUS PEDIDOS JÁ FORAM ENTREGUES</strong></p>
        <?php
        endif;
        ?>

        <?php
        if( $countTotalPedidos == $countEntregasEfetuadas && $entregaEfetuada == 0 ):
        ?>
        <p>Clique em <u>Exibe entregas efetuadas</u> para ver seus pedidos já entregues.</p>
        <?php
        endif;
        ?>

        </td>
    </tr>
</thead>
<tbody>
    <?php
}
    
$shopNameAnt = "...";
$query = "select o.order_id, o.order_no, o.description, s.shop_id, round(sum(invoice_value),2) total "
       . "  from orders o, shops s "
       . " where o.shop_id = s.shop_id "
       . "   and o.created_by = '$user' "
       . ( $entregaEfetuada == 0 ? " and delivery_date_real is null " : "" )
       . " group by o.order_id, o.order_no, o.description, s.shop_id "
       . " order by o.position asc, o.purchase_date desc";

$c->query($query, 1);
while($c->nextRow(1))
{
    $orderId   = $c->getColumnValue("order_id", 1);    
    $orderNo   = $c->getColumnValue("order_no", 1);
    $orderDesc = $c->getColumnValue("description", 1); 
    $shop      = new mpShop($c->getColumnValue("shop_id", 1));
    $shopName  = $shop->getName();
    $shopLink  = $shop->getLinkSite();
    $orderLink = $shop->getLinkOrder($orderNo);
    
    if( $shopNameAnt != $shopName )
    {
      $shopNameAnt = $shopName;
      ?>
      <tr class="shop">
      <td class="buttons">
        <span class="ui-icon ui-icon-arrow-1-n"></span>
        <span class="ui-icon ui-icon-arrow-1-s"></span>
        <span class="ui-icon ui-icon-circle-minus"></span>
      </td>
      <td class="shop" colspan="10">
          <a href="<?php echo $shopLink; ?>" target="_blank"><strong><?php echo $shopName ?></strong></a>
      </td>
      </tr>
      <?php
    }
    
    ?>
    <tr class="order">
    <td class="buttons" valign="top">
      <span class="ui-icon ui-icon-arrow-1-n"></span>
      <span class="ui-icon ui-icon-arrow-1-s"></span>
      <span class="ui-icon ui-icon-trash"></span>
      <div style="width: 62px; text-align: center; diaply: block; clear: both; padding: 2px; border: solid 1px black;">
          <a href="">editar</a>
      </div>
    </td>
    <td class="order" valing="top">
    <p># PEDIDO: <strong><a href="<?php echo $orderLink; ?>" target="_blank"><?php echo $orderNo; ?></a></strong></p>
    <p><i><?php echo $orderDesc; ?>&nbsp;</i></p>
    <p><strong>USD$ <?php echo $c->getColumnValue("total", 1); ?></strong></p>
    </td>
    
    <td valing="top">
    <table class="homeOrderTrackingList" cellspacing="0">
    <?php
    
    $query = "select tracking_id, tracking_no, was_delivered, last_update_date, last_update_info, last_track_data, icon_class "
           . "  from trackings "
           . " where order_id = '{$orderId}' "
           . "   and active = 1 "
           . " order by tracking_id asc;";
    $c->query($query, 2);
    while($c->nextRow(2))
    {
        $trackingNo   = $c->getColumnValue("tracking_no", 2);
        $lastUpdate   = $c->getColumnValue("last_update_date", 2);
        $lastStatus   = $c->getColumnValue("last_update_info", 2);
        $iconClass    = $c->getColumnValue("icon_class", 2);
        
        $lastStatus = preg_replace("/\n/","<br>",$lastStatus);

        $d1 = new DateTime($c->getColumnValue("last_track_data", 2));
        $d2 = new DateTime;
        $diff = $d2->diff($d1);
        
        if( $diff->days == 0 )
        {
            $iconClass__ = "icon-new";
        }
        else
        {
            $iconClass__ = $iconClass;
        }
        
    ?>
        <tr>
            <td rowspan="2" valign="top" class="buttons" style="text-align: right">
              <span class="ui-icon ui-icon-refresh"></span>
              <span class="ui-icon ui-icon-trash"></span>
              <span class="trackingIcon <?php echo $iconClass__; ?>"></span>
            </td>
            <td class="tracking" valign="top"># RASTREIO: <strong><a href="getTrackingInfo.php?tn=<?php echo $trackingNo ?>"><?php echo $trackingNo ?></a></strong></td>
        </tr>
        <tr>
            <td class="lastSatus"><strong><?php echo $lastStatus ?></strong></td>
        </tr>
    <?php
    }
    ?>
    </table>
    </td>
    </tr>
<?php
}
?>
</tbody>
</table>

</div>

<?php

$template->pageFooter();

?>

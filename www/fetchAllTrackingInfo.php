<?php

require_once 'class/mpConfig.php';
require_once 'phpQuery.php';

$sec = new mpSecurity();
if( !$sec->isAuth() && $_SERVER["REMOTE_ADDR"] != "189.38.85.36")
{
  die("Acesso nao Autorizado");
}
else if( $sec->isAuth() && preg_match("/^((elisson\.s@gmail\.com)|(evelin\.kelly@gmail\.com))/i",$sec->getLogedUser()) !== 1 )
{
  die("Acesso nao Autorizado");    
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt_BR">
<head>
<meta charset="utf-8">
</head>
<body>  

<?php

/*
$ct = new mpCompanyTracking();
$ct->setAllParameters( "http://websro.correios.com.br/sro_bin/txect01$.QueryList", 
                       "POST", 
                       array( 'P_LINGUA'  => '"001"', 
                              'P_TIPO'    => '"001"',
                              'P_COD_UNI' => '$tracking->GetNumber()' ), 
                       "company_001", 
                       array( 'linha', 'data', 'local', 'situacao' ), 
                       array( 'data', 'local', 'situacao' ), 
                       array( 'data', 'local', 'situacao' ), 
                       "mpCompany001");

$serialized = serialize($ct);
$serialized = base64_encode($serialized);
echo "<pre>{$serialized}</pre>";
die();
*/

/*
$t = new mpTracking(1);
$c = new Company(1);
$tc = $c->getTrackingClass();
$tc->setTrackingInfo(array(1,2,3,4));
$c->saveTrackingInfo(1);
//var_dump($tc->getTrackingParameters($t));
*/

$mail = new mpMailQueue();

$c = new mpConnect();
$query = "select * from companies where active = 1 and approved_date != ''";
$c->query($query, 1);
while( $c->nextRow(1) )
{
    $company = new mpCompany( $c->getColumnValue("company_id", 1),
                              $c->getColumnValue("name", 1),
                              $c->getColumnValue("description", 1),
                              $c->getColumnValue("link_site", 1),
                              $c->getColumnValue("tracking_class", 1),
                              $c->getColumnValue("active", 1),
                              $c->getColumnValue("created_date", 1),
                              $c->getColumnValue("created_by", 1),
                              $c->getColumnValue("approved_date", 1),
                              $c->getColumnValue("approved_by", 1) );
    
    
    $companyTrackingClass = $company->getTrackingClass();
    
    $last_created_by = "...";
    $query = "select tracking_id, tracking_no, created_by, mail_notify, mail_copy, order_id "
           . "  from trackings "
           . " where active = 1 "
           . "   and was_delivered = 0 "
           . "   and company_id = {$company->getId()} "
           . " order by created_by;";
    $c->query($query, 2);
    while( $c->nextRow(2) )
    {   
        $trackingId   = $c->getColumnValue("tracking_id", 2);
        $trackingNo   = $c->getColumnValue("tracking_no", 2);
        $created_by   = $c->getColumnValue("created_by", 2);
        $mail_notify  = $c->getColumnValue("mail_notify", 2);
        $mail_copy    = $c->getColumnValue("mail_copy", 2);
        $order_id     = $c->getColumnValue("order_id", 2);
        
        $insertMaked = false;
        
        if( $last_created_by != $created_by )
        {
          $last_created_by = $created_by; 
          $user = new mpUser($created_by);
        }
        
        echo "<p>RASTREIO: &nbsp; <strong>{$trackingNo}</strong></p>";

        $className    = $companyTrackingClass->getPhpClassName();
        $resultValues = $className::fetchInfo( $trackingId, $companyTrackingClass, 1 );
        for($i=count($resultValues)-1; $i>=0; $i--)
        {        
            $companyTrackingClass->setTrackingInfo($resultValues[$i]);
            $saveReturn = $company->saveTrackingInfo($trackingId);
            
            if( $saveReturn == "INSERT" )
              $insertMaked = true;
        }
        
        $trackingInfo    = $companyTrackingClass->getStatusColumnsValues(); 
        $lastTrackData   = $className::getLastTrackData( $resultValues ); 
        $wasDelivered    = $className::wasDelivered( $resultValues );
        $iconClass       = $className::getIconClass( $resultValues );
        $columnsToUpdate = array("last_update_info" => "'" . $c->escape($trackingInfo) . "'",
                                 "last_update_date" => "str_to_date('".date("d/m/Y H:i:s")."','%d/%m/%Y %H:%i:%s')",
                                 "last_track_data"  => "str_to_date('" . $lastTrackData . "','%d/%m/%Y %H:%i:%s')",
                                 "icon_class"  => "'" . $iconClass . "'");
                
        if($wasDelivered)
        {
            $columnsToUpdate["was_delivered"] = 1;
            $columnsToUpdate["delivery_date"] = "now()";
            
            $query = $c->buildQuery( "UPDATE", 
                                     "orders", 
                                     array( "delivery_date_real" => "now()" ),
                                     "order_id = {$order_id}");
            $c->query($query);
        }
        
        $query = $c->buildQuery( "UPDATE", 
                                 "trackings", 
                                 $columnsToUpdate,
                                 "tracking_id = {$trackingId}");
        $c->query($query);
        
        // Se houve um INSERT, o usuario configurou para receber emails e o Tracking tambem esta configurado para receber email
        if($insertMaked && $user->getSendMail() == 1 && $mail_notify == 1)
        {
          //Entao insere essa informacao na fila de email
          $mail->addMail( $created_by, $trackingId, $trackingInfo, $mail_copy );
        }       
        
        echo "<hr>";
    }
}

if( $sec->isAuth() )
{
  echo "ENVIA EMAIL<br><br>";
  include 'fetchAllMailQueue.php';
}

?>
</body>
</html>
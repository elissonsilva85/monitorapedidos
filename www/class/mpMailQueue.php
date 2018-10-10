<?php

/*
 * User.php (UTF-8)
 * Desenvolvido por Elisson Silva em 17/08/2014
 */

class mpMailQueue {
    
    private $mail_id;
    private $user_login;
    private $tracking_id;
    private $tracking_info;
    private $mail_copy;
    private $mail_sent;
    private $mail_sent_date;
    private $created_date;
    
    function __construct() {
        $paramsQtde = func_num_args();
        
        if( $paramsQtde == 1 )
        {
            $this->loadMailQueue(func_get_arg(0));
        }
        else if( $paramsQtde > 1 )
        {
            $this->mail_id = func_get_arg(0);
            $this->user_login = func_get_arg(1);
            $this->tracking_id = func_get_arg(2);
            $this->tracking_info = func_get_arg(3);
            $this->mail_copy = func_get_arg(4);
            $this->mail_sent = func_get_arg(5);
            $this->mail_sent_date = func_get_arg(6);
            $this->created_date = func_get_arg(7);
        }
    }
    
    private function loadMailQueue($codigo) {
        $c = new mpConnect();
        
        $codigo = $c->escape($codigo);
        if( $c->query("select * from mail_queue where mail_id = {$codigo}") )
        {
            $c->nextRow();
            $this->mail_id = $c->getColumnValue("mail_id");
            $this->user_login = $c->getColumnValue("user_login");
            $this->tracking_id = $c->getColumnValue("tracking_id");
            $this->tracking_info = $c->getColumnValue("tracking_info");
            $this->mail_copy = $c->getColumnValue("mail_copy");
            $this->mail_sent = $c->getColumnValue("mail_sent");
            $this->mail_sent_date = $c->getColumnValue("mail_sent_date");
            $this->created_date = $c->getColumnValue("created_date");
        }
    }
    
    public function addMail( $userLogin, $trackingId, $trackingInfo, $mailCopy ) {
        $c = new mpConnect();
        
        $userLogin    = $c->escape($userLogin);
        $trackingId   = $c->escape($trackingId);
        $trackingInfo = $c->escape($trackingInfo);
        $mailCopy     = $c->escape($mailCopy);
        
        // Verifica se o tracking já está na fila ...
        $query = "select count(*) existe from mail_queue where user_login = '{$userLogin}' and tracking_id = {$trackingId} and mail_sent = 0";
        $c->query($query);
        $c->nextRow();
        $existe = $c->getColumnValue("existe");
        
        if( $existe == 0 )
        {
          $colunas = array(
            "user_login" => "'{$userLogin}'",
            "tracking_id" => "'{$trackingId}'",
            "tracking_info" => "'{$trackingInfo}'",
            "mail_copy" => "'{$mailCopy}'",
            "created_date" => "str_to_date('".date("d/m/Y H:i:s")."','%d/%m/%Y %H:%i:%s')"
          );
          $query = $c->buildQuery("INSERT", "mail_queue", $colunas);
          $c->query($query);
          
          return true;
        }
        
        return false;
    }
    
    private function getMailBodyHTML( $userName, $trackingInfo ) {
    
      $mailBody = "";      
      $mailBody .= "<table align=\"center\" width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
      $mailBody .= "	<tr>\n";
      $mailBody .= "		<td colspan=\"2\" rowspan=\"3\" width=\"330\"><img src=\"http://monitorapedidos.com.br/images/email/TrackingEmail001.jpg\" width=\"330\" height=\"115\" style=\"border:0px solid;display:block\" alt=\"MonitoraPedidos.com.br\"></td>\n";
      $mailBody .= "		<td colspan=\"2\" width=\"200\"><img src=\"http://monitorapedidos.com.br/images/email/TrackingEmail002.jpg\" width=\"200\" height=\"50\" style=\"border:0px solid;display:block\" alt=\"\"></td>\n";
      $mailBody .= "		<td rowspan=\"4\" width=\"20\"><img src=\"http://monitorapedidos.com.br/images/email/TrackingEmail003.jpg\" width=\"20\" height=\"165\" style=\"border:0px solid;display:block\" alt=\"\"></td>\n";
      $mailBody .= "	</tr>\n";
      $mailBody .= "	<tr>\n";
      $mailBody .= "		<td colspan=\"2\" width=\"200\" height=\"20\" style=\"background: url('http://monitorapedidos.com.br/images/email/TrackingEmail004.jpg');  padding-right: 10px;text-align: right; font-weight: bold; font-size: 14px; font-family: arial, verdana;\" valign=\"bottom\">Olá, {$userName}</td>\n";
      $mailBody .= "	</tr>\n";
      $mailBody .= "	<tr>\n";
      $mailBody .= "		<td colspan=\"2\" width=\"200\"><img src=\"http://monitorapedidos.com.br/images/email/TrackingEmail005.jpg\" width=\"200\" height=\"45\" style=\"border:0px solid;display:block\" alt=\"\"></td>\n";
      $mailBody .= "	</tr>\n";
      $mailBody .= "	<tr>\n";
      $mailBody .= "		<td width=\"150\"><img src=\"http://monitorapedidos.com.br/images/email/TrackingEmail006.jpg\" width=\"150\" height=\"50\" style=\"border:0px solid;display:block\" alt=\"\"></td>\n";
      $mailBody .= "		<td width=\"300\" height=\"50\" colspan=\"2\" style=\"background: url('http://monitorapedidos.com.br/images/email/TrackingEmail007.jpg'); width: 300px; height: 50px; text-align: left; font-weight: bold; font-size: 12px; font-family: arial, verdana;\">\n";
      $mailBody .= "              Opa! Que beleza!<br>\n";
      $mailBody .= "              Houve uma atualização em um dos seus rastreios<br>\n";
      $mailBody .= "              Verifique abaixo as novidades:\n";
      $mailBody .= "            </td>\n";
      $mailBody .= "		<td width=\"80\"><img src=\"http://monitorapedidos.com.br/images/email/TrackingEmail008.jpg\" width=\"80\" height=\"50\" style=\"border:0px solid;display:block\" alt=\"\"></td>\n";
      $mailBody .= "	</tr>\n";
      $mailBody .= "	<tr>\n";
      $mailBody .= "		<td width=\"550\" colspan=\"5\"><img src=\"http://monitorapedidos.com.br/images/email/TrackingEmail009.jpg\" width=\"550\" height=\"25\" style=\"border:0px solid;display:block\" alt=\"\"></td>\n";
      $mailBody .= "	</tr>\n";
      $mailBody .= "	<tr>\n";
      $mailBody .= "		<td width=\"550\" colspan=\"5\" style=\"background: url('http://monitorapedidos.com.br/images/email/TrackingEmail010.jpg') no-repeat; width: 550px; height: 95px; font-family: arial, verdana;\">\n";
      $mailBody .= "			<table cellspacing=\"0\" cellpadding=\"10\" style=\"margin-left: 15px\">{$trackingInfo}</table>\n";
      $mailBody .= "		</td>\n";
      $mailBody .= "	</tr>\n";
      $mailBody .= "	<tr>\n";
      $mailBody .= "		<td width=\"550\" colspan=\"5\"><img src=\"http://monitorapedidos.com.br/images/email/TrackingEmail011.jpg\" width=\"550\" height=\"15\" style=\"border:0px solid;display:block\" alt=\"\"></td>\n";
      $mailBody .= "	</tr>\n";
      $mailBody .= "</table>\n";

      $mailBody .= "<br>";
      
      return $mailBody; 
    }
    
    private function getMailBodyPLAIN( $trackingInfo ) {
    
      $mailBody = "";

      $mailBody .= "Opa! Que beleza!\n";
      $mailBody .= "Houve uma atualização em um dos seus rastreios.\n";
      $mailBody .= "Verifique abaixo as novidades:\n\n";
      $mailBody .= $trackingInfo;
      $mailBody .= "\n";
      
      return $mailBody; 
    }
    
    private function getTrackingMailInfoHTML( $tracking_id, $tracking_info ) {
    
      $tracking = new mpTracking($tracking_id);
      $order    = $tracking->getOrder();
      
      $orderNo    = $order->getNumber();
      $orderDesc  = $order->getDescription(); 
      $orderValue = $order->getValue();
      //$orderLink = $shop->getLinkOrder($orderNo); // "<a href=\"{$orderLink}\" target=\"_blank\"></a>"
      
      $trackingNo = $tracking->getNumber();   
      $lastStatus = preg_replace("/\n/","<br>",$tracking_info);   
            
      $trackingMailInfo  = "";
      $trackingMailInfo .= "      <tr>\n";
      $trackingMailInfo .= "        <td valign=\"top\" style=\"font-size: 12px;\">\n";
      $trackingMailInfo .= "          <p># PEDIDO: <strong>{$orderNo}</strong></p>\n";
      $trackingMailInfo .= "          <p><i>{$orderDesc}</i></p>\n";
      $trackingMailInfo .= "          <p><strong>USD$ {$orderValue}</strong></p>\n";
      $trackingMailInfo .= "        </td>\n";
      $trackingMailInfo .= "        <td valign=\"top\" style=\"font-size: 12px;\">\n";
      $trackingMailInfo .= "          <p># RASTREIO: <strong>{$trackingNo}</strong></p>\n";
      $trackingMailInfo .= "          <p><strong>{$lastStatus}</strong></p>\n";
      $trackingMailInfo .= "        </td>\n";
      $trackingMailInfo .= "      </tr>\n";
      $trackingMailInfo .= "\n";

      return $trackingMailInfo;      
    }
    
    private function getTrackingMailInfoPLAIN( $tracking_id, $tracking_info ) {
    
      $tracking = new mpTracking($tracking_id);
            
      $trackingMailInfo  = "";
      $trackingMailInfo .= "# RASTREIO: {$tracking->getNumber()}\n"; 
      $trackingMailInfo .= $tracking_info . "\n";
      
      return $trackingMailInfo;
    }
    
    public function getTrackingMailInfo( $tracking_id, $tracking_info, $mailType ) {
    
      $trackingMailInfo = "...";
      switch( $mailType )
      {
        case "HTML":
          $trackingMailInfo = $this->getTrackingMailInfoHTML( $tracking_id, $tracking_info );       
          break;
        
        case "PLAIN":
        default:
          $trackingMailInfo = $this->getTrackingMailInfoPLAIN( $tracking_id, $tracking_info );
          break;
      }
      
      return $trackingMailInfo;    
    }
    
    public function sendMail( $to, $copy, $body, $mailType, $userName ) {
    
      $mailBody = "...";
      switch( $mailType )
      {
        case "HTML":
          $mailBody = $this->getMailBodyHTML( $userName, $body );       
          break;
        
        case "PLAIN":
        default:
          $mailBody = $this->getMailBodyPLAIN( $userName, $body );
          break;
      }
      
      $mail = new mpMail();
      return $mail->sendMail($to, $copy, "MonitoraPedidos.com.br - Atualização de Status", $mailBody, $mailType);
    }
    
    public function setMailSent($mail_id)
    {
      $c = new mpConnect();
      $query = $c->buildQuery( "UPDATE", 
                               "mail_queue", 
                               array("mail_sent" => "1",
                                     "mail_sent_date" => "str_to_date('".date("d/m/Y H:i:s")."','%d/%m/%Y %H:%i:%s')"), 
                               "mail_id = {$mail_id}");
      $c->query($query); 
    }
    
}

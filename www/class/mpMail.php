<?php

/*
 * User.php (UTF-8)
 * Desenvolvido por Elisson Silva em 17/08/2014
 */

class mpMail {

    private function getMailBodyHTML( $body ) {
    
      $mailBody = "";
      
      $mailBody .= "<html>";
      $mailBody .= "<head>";
      $mailBody .= "   <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
      $mailBody .= "   <title>MonitoraPedidos.com.br</title>";
      $mailBody .= "</head>";
      $mailBody .= "<body>";
      $mailBody .= $body;
      $mailBody .= "<br>";
      $mailBody .= "<table align=\"center\"><tr><td>Obrigado por escolher MonitoraPedidos.com.br</td></tr></table>";
      $mailBody .= "</body>";
      $mailBody .= "</html>";
      
      return $mailBody; 
    }
    
    private function getMailBodyPLAIN( $body ) {
    
      $mailBody = "";

      $mailBody .= $body;
      $mailBody .= "\n";
      $mailBody .= "Obrigado por escolher MonitoraPedidos.com.br";
      
      return $mailBody; 
    }
    
    public function sendMail( $to, $copy, $subject, $body, $mailType = "HTML" ) {
    
      $mailBody = "...";
      switch( $mailType )
      {
        case "HTML":
          $mailBody = $this->getMailBodyHTML( $body );       
          break;
        
        case "PLAIN":
        default:
          $mailBody = $this->getMailBodyPLAIN( $body );
          break;
      }
      
      $headers = "MIME-Version: 1.0" . "\r\n" . 
                 "Content-type: " . ( $mailType == "HTML" ? "text/html" : "text/plain" ) . ";  charset=utf-8 \r\n" .
                 "From: Monitora Pedidos <noreply@monitorapedidos.com.br>" . "\r\n" .
                 "Reply-To: Monitora Pedidos <contato@monitorapedidos.com.br>" . "\r\n" .
                 "Return-Path: <contato@monitorapedidos.com.br>" . "\r\n" .
                 "X-Mailer: PHP/" . phpversion() . "\r\n";
      
      return mail($to, $subject, $mailBody, $headers);
    
    }
        
}

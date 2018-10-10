<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt_BR">
<head>
<meta charset="utf-8">
</head>
<body>  
<?php

require_once 'class/mpConfig.php';

$sec = new mpSecurity();
if( !$sec->isAuth() && $_SERVER["REMOTE_ADDR"] != "189.38.85.36")
{
  die("Acesso nao Autorizado");
}
else if( $sec->isAuth() && preg_match("/^((elisson\.s@gmail\.com)|(evelin\.kelly@gmail\.com))/i",$sec->getLogedUser()) !== 1 )
{
  die("Acesso nao Autorizado");    
}

$last_user_login = "...";
$_emailTo   = "";
$_emailCopy = "";
$_emailBody = "";

$mail = new mpMailQueue();

$c = new mpConnect();
$query = "select * from mail_queue where mail_sent = 0 order by user_login";
$c->query($query, 1);
while( $c->nextRow(1) )
{
    $mail_id = $c->getColumnValue("mail_id", 1);
    $user_login = $c->getColumnValue("user_login", 1);
    $tracking_id = $c->getColumnValue("tracking_id", 1);
    $tracking_info = $c->getColumnValue("tracking_info", 1);
    $mail_copy = $c->getColumnValue("mail_copy", 1);
    $mail_sent = $c->getColumnValue("mail_sent", 1);
    $mail_sent_date = $c->getColumnValue("mail_sent_date", 1);
    $created_date = $c->getColumnValue("created_date", 1);

    if( $user_login != $last_user_login )
    {
      if($_emailBody != "")
      {
        if($mail->sendMail($_emailTo, $_emailCopy, $_emailBody, $user->getMailFormat(), $user->getName()))
        {
          $mail->setMailSent($mail_id);
          echo "SUCESSO NO ENVIO DO EMAIL! (1)";
        }
        else
        {
          echo "FALHA NO ENVIO DE EMAIL! (1)";
        }
                
        $_emailTo   = "";
        $_emailCopy = "";
        $_emailBody = "";
      }
    
      $last_user_login = $user_login;
      $user = new mpUser($user_login); 
    }

    if( $user->getMailGrouping() == 1 )
    {
      $_emailTo    = $last_user_login;
      $_emailBody .= $mail->getTrackingMailInfo($tracking_id, $tracking_info, $user->getMailFormat() );
      
      if( $mail_copy != "" && $_emailCopy == "" )
        $_emailCopy = $mail_copy;
      else if( $mail_copy != "" && strpos($_emailCopy, $mail_copy) !== false )
        $_emailCopy .= "," . $mail_copy; 
    }
    else
    {
      $_emailTo   = $last_user_login;
      $_emailCopy = $mail_copy;
      $_emailBody = $mail->getTrackingMailInfo($tracking_id, $tracking_info, $user->getMailFormat() );
      echo "[1:{$user->getMailFormat()}]";
      echo "[2:{$user->getName()}]";
      if($mail->sendMail($_emailTo, $_emailCopy, $_emailBody, $user->getMailFormat(), $user->getName()))
      {
        $mail->setMailSent($mail_id);
        echo "SUCESSO NO ENVIO DO EMAIL! (2)";
      }
      else
      {
        echo "FALHA NO ENVIO DE EMAIL! (2)";
      }  
      
      $_emailTo   = "";
      $_emailCopy = "";
      $_emailBody = "";
    }
    
    echo "<hr>";
}

if($_emailBody != "")
{
  if($mail->sendMail($_emailTo, $_emailCopy, $_emailBody, $user->getMailFormat(), $user->getName()))
  {
    $mail->setMailSent($mail_id);
    echo "SUCESSO NO ENVIO DO EMAIL! (3)";
  }
  else
  {
    echo "FALHA NO ENVIO DE EMAIL! (3)";
  }  
}

?>
</body>
</html>
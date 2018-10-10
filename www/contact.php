<?php

require_once 'class/mpConfig.php';

$sec = new mpSecurity();
if( !$sec->isAuth() )
{
    $sec->redirectLoginPage();
}

$template = new mpTemplate("contato");
$template->addDefaultJS();
$template->addDefaultCSS();

$template->pageHeader();
$template->pageToolset(5,"CONTATO");

$msgTela = "";
$assunto = "";
if( isset($_POST['submit']) )
{   
    $body = "{$sec->getLogedUserName()} ({$sec->getLogedUser()}) entrou em contato com um(a) {$_POST['assunto']}.\nSegue abaixo a mensagem dele(a):\n\n{$_POST['mensagem']}\n\n";
    $mail = new mpMail();
    if( $mail->sendMail("contato@monitorapedidos.com.br", null, "Contato - {$_POST['assunto']}", $body, "PLAIN") )
    {
        $msgTela = "<strong style=\"color: green\">Obrigado pelo seu contato. Retornaremos assim que possível.</strong>";
    }
    else
    {
        $msgTela = "<strong style=\"color: red\">Desculpe, houve uma falha ao enviar a mensagem.<br>Entre em contato através do email contato@monitorapedidos.com.br.</strong>";
    }
}
else if( isset($_GET['l']) )
{
  $assunto = "4"; // Inclusão de Loja
}
else if( isset($_GET['t']) )
{
  $assunto = "5"; // Inclusão de Transportadora
}

?>
<div class="container">
    
    <form method="POST">
    <table cellspacing="0" cellpadding="10">
        <tr>
            <td>&nbsp;</td>
            <td><?php echo $msgTela; ?></td>
        </tr>
        <tr>
            <td align="right" width="100">Assunto:</td>
            <td><select name="assunto" style="width:100%">
                    <option <?php if($assunto == 1) echo "selected"; ?>>Duvida</option>
                    <option <?php if($assunto == 2) echo "selected"; ?>>Crítica</option>
                    <option <?php if($assunto == 3) echo "selected"; ?>>Sugestão</option>
                    <option <?php if($assunto == 4) echo "selected"; ?>>Inclusão de Loja</option>
                    <option <?php if($assunto == 5) echo "selected"; ?>>Inclusão de Transportadora</option>
                    <option <?php if($assunto == 6) echo "selected"; ?>>Problema no Sistema</option>
                </select></td>
        </tr>
        <tr>
            <td align="right" valign="top">Mensagem:</td>
            <td><textarea name="mensagem" id="mensagem" rows="5" cols="60"></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value=" ENVIAR "></td>
        </tr>
    </table>    
    </form>
    
</div>

<script>
    document.getElementById("mensagem").focus();
</script>

<?php

$template->pageFooter();

?>
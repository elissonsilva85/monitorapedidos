<?php

require_once 'class/mpConfig.php';

$titulo = "MEUS DADOS";
$nomeBotao = "SALVAR DADOS";

$sec = new mpSecurity();
if( !$sec->isAuth() )
{
    $titulo = "CRIAR CONTA";
    $nomeBotao = "CRIAR NOVA CONTA";
}

$form_login = "";
$form_nome = "";
$form_cidade = "";
$form_pais = "";
$form_idioma = "";
$form_notifica_email = "";
$form_agrupa_email = "";
$form_formato_email = "";

$form_error = 0;
$form_error_msg = "";

$form_sucess = 0;
$form_sucess_msg = 0;

if( isset($_POST['submit']) )
{
    $form_nome = $_POST['nome'];
    $form_cidade = $_POST['cidade'];
    $form_pais = $_POST['pais'];
    $form_idioma = $_POST['idioma'];
    $form_notifica_email = $_POST['notifica_email'];
    $form_agrupa_email = $_POST['agrupa_email'];
    $form_formato_email = $_POST['formato_email'];

    $user = new mpUser();
    if( $sec->isAuth() )
    {
        $form_login = $sec->getLogedUser();
        try {
            $user->updateUser( $_POST['nome'], $_POST['senha_antiga'], $_POST['senha'], $_POST['senha_confirma'], $_POST['cidade'], $_POST['pais'], $_POST['idioma'], $_POST['notifica_email'], $_POST['agrupa_email'], $_POST['formato_email'] );
            $form_sucess = 1;
            $form_sucess_msg = "Dados alterado com sucesso.";
        } catch (Exception $ex) {
            $form_error = 1;
            $form_error_msg = $ex->getMessage();
        }
    }
    else
    {
        $form_login = $_POST['login'];
        try { 
            $user->createUser( $_POST['login'], $_POST['nome'], $_POST['senha'], $_POST['senha_confirma'], $_POST['cidade'], $_POST['pais'], $_POST['idioma'], $_POST['notifica_email'], $_POST['agrupa_email'], $_POST['formato_email'] );
            $form_sucess = 1;
            $_SESSION['first_login_email'] = $_POST['login'];
            $form_sucess_msg = "<br>Seu usuário foi criado com sucesso.<br>"
                             . "Consulte sua caixa de entrada e valide seu email.<br><br><br>"
                             . "<a href=\"index.php\">CLIQUE AQUI PARA ACESSAR SUA CONTA</a>";
        } catch (Exception $ex) {
            $form_error = 1;
            $form_error_msg = $ex->getMessage();
        }
    }
    
}
else if( $sec->isAuth() )
{
    $user = new mpUser( $sec->getLogedUser() );
    $form_login = $sec->getLogedUser();
    $form_nome = $user->getName();
    $form_cidade = $user->getCity();
    $form_pais = $user->getCountry();
    $form_idioma = $user->getLanguage();
    $form_notifica_email = $user->getSendMail();
    $form_agrupa_email = $user->getMailGrouping();
    $form_formato_email = $user->getMailFormat();
}

$template = new mpTemplate("user");
$template->addDefaultJS();
$template->addDefaultCSS();

$template->pageHeader();
$template->pageToolset(2,$titulo);

?>
<div class="container" style="position: relative; top: -30px;">

<form method="POST">
<table cellspacing="0" cellpadding="10">
<tr>
    <td align="right"><span class="mandatory"><?php if( $sec->isAuth() || $form_sucess == 0 ) echo "(*) Obrigatório"; ?></span> </td>    
    <?php
    if( $form_error == 1 ):
    ?>
    <td><strong style="color: red"><?php echo $form_error_msg; ?></strong></td>
    <?php
    elseif( $form_sucess == 1 ):
    ?>
    <td><strong style="color: green"><?php echo $form_sucess_msg; ?></strong></td>
    <?php
    else:
    ?>
    <td></td>
    <?php
    endif;
    ?>
</tr>
<?php
if( $sec->isAuth() || $form_sucess == 0 ):
?>
<tr>
    <td align="right">E-mail <?php if(!$sec->isAuth()) echo "<span class=\"mandatory\">(*)</span>"; ?></td>
    <?php
    if( $sec->isAuth() ):
    ?>
    <td><strong><?php echo $form_login ?></strong></td>
    <?php
    else:
    ?>
    <td><input name="login" type="text" size="40" value="<?php echo $form_login ?>"></td>
    <?php
    endif;
    ?>
  </tr>
  <tr>
    <td align="right">Nome <span class="mandatory">(*)</span></td>
    <td><input name="nome" type="text" size="40" value="<?php echo $form_nome ?>"></td>
  </tr>
  <?php
  if( $sec->isAuth() ):
  ?>
  <tr>
    <td colspan="2"><hr></td>
  </tr>
  <tr>
    <td align="right">Senha Antiga</td>
    <td><input name="senha_antiga" type="password" size="40"></td>
  </tr>
  <?php
  endif;
  ?>
  <tr>
    <td align="right">Senha <?php if($sec->isAuth()) echo "Nova"; ?> <?php if(!$sec->isAuth()) echo "<span class=\"mandatory\">(*)</span>"; ?></td>
    <td><input name="senha" type="password" size="40"></td>
  </tr>
  <tr>
    <td align="right">Redigite a Senha <?php if($sec->isAuth()) echo "Nova"; ?> <?php if(!$sec->isAuth()) echo "<span class=\"mandatory\">(*)</span>"; ?></td>
    <td><input name="senha_confirma" type="password" size="40"></td>
  </tr>
  <tr>
    <td colspan="2"><hr></td>
  </tr>
  <tr>
    <td align="right">Cidade</td>
    <td><input name="cidade" type="text" size="40" value="<?php echo $form_cidade ?>"></td>
  </tr>
  <tr>
    <td align="right">Pais</td>
    <td><select name="pais">
            <option value="BR" <?php echo ($form_pais == "BR" ? "selected" : "" ) ?>>Brasil</option>
        </select></td>
  </tr>
  <tr>
    <td align="right">Idioma</td>
    <td><select name="idioma">
            <option value="pt-BR" <?php echo ($form_idioma == "pt-br" ? "selected" : "" ) ?>>Portugues Brasileiro</option>
        </select></td>
  </tr>
  <tr>
    <td align="right">Notificar por email ?</td>
    <td><select name="notifica_email">
            <option value="1" <?php echo ($form_notifica_email == "1" ? "selected" : "" ) ?>>Sim, desejo receber um email quando houver alteração no status do pacote</option>
            <option value="0" <?php echo ($form_notifica_email == "0" ? "selected" : "" ) ?>>Não, não quero receber nenhum email</option>
        </select></td>
  </tr>
  <tr>
    <td align="right">Deseja agrupar todas as notificações ?</td>
    <td><select name="agrupa_email">
            <option value="1" <?php echo ($form_agrupa_email == "1" ? "selected" : "" ) ?>>Sim, desejo receber apenas 1 email agrupando todas as notificações</option>
            <option value="0" <?php echo ($form_agrupa_email == "0" ? "selected" : "" ) ?>>Não, quero receber 1 email individual para cada notificação</option>
        </select></td>
  </tr>
  <tr>
    <td align="right">Formato do Email</td>
    <td><select name="formato_email">
            <option value="HTML" <?php echo ($form_formato_email == "HTML" ? "selected" : "" ) ?>>HTML</option>
            <option value="PLAIN" <?php echo ($form_formato_email == "PLAIN" ? "selected" : "" ) ?>>Texto</option>
        </select></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" name="submit" value="<?php echo $nomeBotao; ?>"></td>
  </tr>
</table>
<?php
endif;
?>
</form>

</div>

<?php

$template->pageFooter();

?>
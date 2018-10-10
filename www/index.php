<?php

require_once 'class/mpConfig.php';

$sec = new mpSecurity();
if( $sec->isAuth() )
{
    $sec->redirectHomePage();
}
    
$template = new mpTemplate("index");
$template->addDefaultCSS();

$error = "";
$userInput = "";
if( isset($_SESSION['first_login_email']) )
{
    $userInput = $_SESSION['first_login_email'];
    unset($_SESSION['first_login_email']);
}
if( isset($_POST['submit']) && isset($_POST['user']) && isset($_POST['pass']) )
{
    $userInput = $_POST['user'];

    if( ($error = $sec->autenticate($_POST['user'], $_POST['pass'])) === true )
    {
        $sec->redirectHomePage();
    }
}

$template->pageHeader();
?>

<form method="POST">
<div class="boxLogon">
    <div class="top"></div>
    <div class="middle">
        <div>
            <div class="left"><span>E-mail:</span></div>
            <div class="right"><input type="text" name="user" id="user" value="<?php echo $userInput; ?>"></div>
        </div>                
        <div>
            <div class="left"><span>Senha:</span></div>
            <div class="right"><input type="password" name="pass" id="pass"></div>
        </div>                
        <div>
            <div class="left">&nbsp;</div>
            <div class="right">
                <table width="100%">
                    <tr>
                        <td width="200"><a href="userInfo.php">Criar uma conta</a> <br>
                            <a href="">Esqueci minha senha</a></td>
                        <td><button name="submit">ENTRAR</button></td>
                    </tr>
                </table>
                
            </div>
        </div> 
        <?php
        if($error != "")
        {
            ?>
            <div class="error">
                <span><?php echo $error; ?></span>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="bottom"></div>

</div>
</form>

<?php
if( strlen($userInput) > 0 ):
?>
<script>
    document.getElementById("pass").focus();
</script>
<?php
else:
?>
<script>
    document.getElementById("user").focus();
</script>
<?php
endif;
?>

<div class="boxLogonAdSense">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- MonitoraPedidos 001 (login) -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-0438395860459322"
     data-ad-slot="4347181508"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>

<?php
$template->pageFooter();
?>

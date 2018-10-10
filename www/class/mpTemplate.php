<?php

/*
 * Template.php (UTF-8)
 * Desenvolvido por Elisson Silva em 20/06/2014
 */

class mpTemplate {
    
    private $pageName;
    private $css;
    private $js;
    
    function __construct($pageName) {
        $this->pageName = $pageName;
        $this->css = [];
        $this->js = [];
    }
    
    public function addCSS($url) {
        $this->css[] = $url;
    }
    
    public function addDefaultCSS() {
        $this->addCSS("css/pace.min.css");
        $this->addCSS("css/jquery-ui.min.css");
        $this->addCSS("css/default.min.css");
    }
    
    private function includeCSS() {
        
        foreach($this->css as $url)
        {
            ?><link rel="stylesheet" type="text/css" href="<?php echo $url; ?>" />
            <?php
        }
        
    }

    public function addJS($url) {
        $this->js[] = $url;
    }
    
    public function addDefaultJS() {
        $this->addJS("js/jquery.min.js");
        $this->addJS("js/jquery-ui.min.js");
        $this->addJS("js/pace.min.js");
    }
    
    private function includeJS() {
        
        foreach($this->js as $url)
        {
            ?><script src="<?php echo $url; ?>"></script>
            <?php
        }
        
    }
    
    public function pageHeader() {
        ?>
<!DOCTYPE html>
<!--
<?php echo $this->pageName; ?>.php (UTF-8)
Desenvolvido por Elisson Silva em 20/06/2014
-->
<html>
<head>
    <meta charset="UTF-8">
    <title>MonitoraPedidos.com.br</title>
    <?php $this->includeJS(); ?>
    <?php $this->includeCSS(); ?>
    
</head>
<body>
        <?php                    
    }
    
    public function pageFooter() {
        ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-56063112-1', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>
        <?php
    }
    
    public function pageToolset($type, $title) {
        $sec = new mpSecurity();
        if( !is_int($type) || $type < 1 || $type > 6 )
            $type = 1;
        ?>

<div class="TopBanner">
    <div class="left type<?php echo $type; ?>"></div>
    <div class="middle"></div>
    <div class="right"></div>
    <div class="toolset">
        <?php
        if( $sec->isAuth() ):
        ?>
        <div class="botoes">
            <a href="home.php" class="<?php if($title == "PEDIDOS") echo "activated" ?>">PEDIDOS</a>
            <a href="shops.php" class="<?php if($title == "LOJAS") echo "activated" ?>">LOJAS</a> |
            <a href="getTrackingInfo.php" class="<?php if($title == "RASTREAR") echo "activated" ?>">RASTREAR</a>
            <a href="userInfo.php" class="<?php if($title == "MEUS DADOS") echo "activated" ?>">MEUS DADOS</a> |
            <a href="contact.php" class="<?php if($title == "CONTATO") echo "activated" ?>">CONTATO</a>
            <?php
            if( preg_match("/^((elisson\.s@gmail\.com)|(evelin\.kelly@gmail\.com))/i",$sec->getLogedUser()) == 1 ):
            ?>
            | <a href="fetchAllTrackingInfo.php" target="_blank">FORÇAR ATUALIZAÇÃO</a>
            <?php
            endif;
            ?>
        </div>
        <?php
        endif;
        ?>
        <div class="logoff">
            <?php
            if( $sec->isAuth() ):
            ?>
            <span class="user">Olá <?php echo $sec->getLogedUserName(); ?>, seja bem vindo(a)</span>
            <?php
            endif;
            ?>
            <span><a href="logoff.php">SAIR</a></span>
        </div>
    </div>
    <div class="title">
        <h3><?php echo $title; ?></h3>
    </div>
</div>

<div style="position: absolute; top: 250px; right: 25px; margin-bottom: 25px; z-index: 10;">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- MonitoraPedidos 002 (inside right) -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:600px"
     data-ad-client="ca-pub-0438395860459322"
     data-ad-slot="2870448302"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>

<div style="position: absolute; top: 0px; right: 25px; z-index: 10;">
<div style="display: block; clear: both; margin: 10px 0;">AJUDE A MANTER ESSE SITE CLICANDO NOS ANUNCIOS</div>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- MonitoraPedidos 003 (inside top) -->
<ins class="adsbygoogle"
     style="display:inline-block;width:320px;height:100px"
     data-ad-client="ca-pub-0438395860459322"
     data-ad-slot="8740355104"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
        <?php
    }
    
    
}

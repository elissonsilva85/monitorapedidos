<?php

require_once 'class/mpConfig.php';
$error = $_SESSION['error_message'];
    
$template = new mpTemplate("index");
$template->addDefaultCSS();

$template->pageHeader();
?>

<form method="POST">
<div class="boxLogon">
    <div class="top"></div>
    <div class="middle">
        <div class="error">
            <span><?php echo $error; ?></span>
        </div>
    </div>
    <div class="bottom"></div>

</div>
</form>

<div class="boxLogonAdSense">
<script type="text/javascript">
    google_ad_client = "ca-pub-0438395860459322";
    google_ad_slot = "4347181508";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- MonitoraPedidos 002 -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>

<?php
$template->pageFooter();
?>

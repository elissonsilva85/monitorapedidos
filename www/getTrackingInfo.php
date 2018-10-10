<?php

require_once 'class/mpConfig.php';
require_once 'phpQuery.php';

$sec = new mpSecurity();
if( !$sec->isAuth() )
{
    $sec->redirectLoginPage();
}

$template = new mpTemplate("getTrackingInfo");
$template->addDefaultJS();
$template->addDefaultCSS();

$template->pageHeader();
$template->pageToolset(1,"RASTREAR");

?>
<div class="container">
    <form method="GET">
        <table>
            <tr>
                <td align="right">Transportadora:</td>
                <td><select>
                        <option>Correios</option>
                    </select></td>
            </tr>
            <tr>
                <td align="right">Cód. Rastreio:</td>
                <td><input type=text" name="tn" size="18" value="<?php echo ( isset($_GET["tn"]) ? $_GET["tn"] : "" ); ?>"> &nbsp;&nbsp;
                    <input type="submit" value=" CONSULTAR "></td>
            </tr>
        </table>
    </form>
</div>

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
                       "Company001");

$serialized = serialize($ct);
$serialized = base64_encode($serialized);
echo "<pre>{$serialized}</pre>";
*/

/*
$t = new mpTracking(1);

$c = new mpCompany(1);
$tc = $c->getTrackingClass();
$tc->setTrackingInfo(1,2,3,4);
$c->saveTrackingInfo(1);
*/

//var_dump($tc->getTrackingParameters($t));

if( isset($_GET["tn"]) )
{
    $trackNumber = $_GET["tn"];
    $baseUrl = "http://websro.correios.com.br/sro_bin/txect01$.QueryList";
    $baseParams = array( 'P_LINGUA'  => '001', 
                         'P_TIPO'    => '001',
                         'P_COD_UNI' => $trackNumber );
    
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $baseUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $baseParams);
    
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    
    // grab URL and pass it to the browser
    $result = curl_exec($ch);

    // close cURL resource, and free up system resources
    curl_close($ch);
    
    $tableFind   = false;
    $resultFinal = "";
    $resultLine  = explode("\n", $result);
    foreach($resultLine as $line)
    {
        if(preg_match("/<table*/",$line) == 1)
            $tableFind = true;
        
        if($tableFind)
            $resultFinal .= $line . "\n";
        
        if(preg_match("/<\/TABLE>*/",$line) == 1)
            $tableFind = false;
    }
    
    phpQuery::newDocumentHTML($resultFinal, $charset = 'utf-8');
    //phpQuery::$debug = true;
    $tableLines = pq("tr");
    $lineCount = $tableLines->count();
    
    function ehData($str) {
        return preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4}) ([0-9]{2}):([0-9]{2})/",$str) == 1;
    }
    
    echo "<table border='1' cellspacing='0' cellpadding='10' style='margin: 10px;'>";
    echo "<tr> <th>DATA</th> <th>LOCAL</th> <th>SITUAÇÃO</th> </tr>";
    $dataAnt = "...";
    for($i=1; $i<$lineCount; $i++)
    {    
        $data = pq("tr:eq({$i}) td:eq(0)")->text();
        $local = pq("tr:eq({$i}) td:eq(1)")->text();
        $situacao = pq("tr:eq({$i}) td:eq(2) font")->text();
        
        if(!ehData($data))
        {
            $local = $data;
            $data  = $dataAnt;
        }
        
        echo "<tr>";
        echo "<td>{$data}</td>";
        echo "<td>{$local}</td>";
        echo "<td>{$situacao}</td>";
        echo "</tr>";
        $dataAnt = $data;
    }
    echo "</table>";
    
    
}
?>

<?php

$template->pageFooter();

?>
<?php

/*
 * Project.php (UTF-8)
 * Desenvolvido por Elisson Silva em 21/07/2014
 */

//require_once './../Tracking.php';

/**
 * Classe Project
 */
class mpCompany001 {
    
    private static function ehData($str) {
        return preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4}) ([0-9]{2}):([0-9]{2})/",$str) == 1;
    }
    
    public static function wasDelivered( $finalResult )
    {
        if(is_array($finalResult))
        {
            for($i=0; $i<count($finalResult); $i++)
                if($finalResult[$i][3] == "Entrega Efetuada")
                    return true;
        }
        
        return false;
    }

    public function getLastTrackData($finalResult) {
        $return = "";
        try{
            $return = $finalResult[0][1];
        } catch(Exception $e) {
        }
        
        return $return;
    }
    
    public function getIconClass($finalResult) {
        $return = "icon-plane";
        try {
            switch ($return = $finalResult[0][3])
            {
                case "Entrega Efetuada":
                    $return = "icon-check";
                    break;
                case "Saiu para entrega ao destinatário":
                    $return = "icon-truck";
                    break;
                case "Conferido":
                case "Encaminhado":
                    $return = "icon-fragile";
                    break;
                case "Postado":
                    $return = "icon-45degree-box";
                    break;
                default:
                    $return = "icon-plane";
                    break;
            }
            
        } catch(Exception $e) {
        }
        
        return $return;
    }

    public static function fetchInfo( $trackingId, $companyTracking, $debug = 0 ) {
        
        $baseUrl = $companyTracking->getHttpURL();
        $baseParams = $companyTracking->getTrackingParameters( new mpTracking( $trackingId ) );

        // create a new cURL resource
        $ch = curl_init();
        
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $baseParams);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        if($debug==1)
            echo "<strong>HTTP CODE:</strong> {$status}";

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
        
        phpQuery::newDocumentHTML($resultFinal, $charset = 'iso-8859-1');
        //phpQuery::$debug = true;
        $tableLines = pq("tr");
        $lineCount = $tableLines->count();

        if($debug==1)
        {
            echo "<table border=1 cellspacing=0 cellpadding=7>";
            echo "<tr> <th>#</th> <th>DATA</th> <th>LOCAL</th> <th>SITUAÇÃO</th> </tr>";
        }
        $dataAnt = "...";
        $finalResult = array();
        for($i=1; $i<$lineCount; $i++)
        {    
            $data = utf8_decode(pq("tr:eq({$i}) td:eq(0)")->text());
            $local = utf8_decode(pq("tr:eq({$i}) td:eq(1)")->text());
            $situacao = utf8_decode(pq("tr:eq({$i}) td:eq(2) font")->text());

            if(!self::ehData($data))
            {
                $local = $data;
                $data  = $dataAnt;
            }
            $dataAnt = $data;

            if($debug==1)
            {                
                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>{$data}</td>";
                echo "<td>{$local}</td>";
                echo "<td>{$situacao}</td>";
                echo "</tr>";
            }
            
            $finalResult[] = array( $i, $data, $local, $situacao );
        }
        if($debug==1)
            echo "</table>";
        
        return $finalResult;
    }
    
}
		
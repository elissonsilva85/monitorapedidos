<?php

/*
 * Project.php (UTF-8)
 * Desenvolvido por Elisson Silva em 21/07/2014
 */

class mpCompanyTracking {
    private $httpURL;
    private $httpMethod;
    private $httpParameters;    
    private $dbTable;
    private $dbTableCustomColumns;
    private $dbTableCustomColumnsValues;
    private $dbTableStatusColumns;
    private $dbTableHashColumns;    
    private $phpClassName;
    
    function __construct() {
        $this->httpParameters = array();
        $this->dbTableCustomColumns = array();
        $this->dbTableCustomColumnsValues = array();
        $this->dbTableStatusColumns = array();
        $this->dbTableHashColumns = array();
    }
    
    public function setAllParameters( $_httpURL,  $_httpMethod,  $_httpParameters,  $_dbTable,  $_dbTableCustomColumns,  $_dbTableStatusColumns, $_dbTableHashColumns,  $_phpClassName ) {
        $this->httpURL = $_httpURL;
        $this->httpMethod = $_httpMethod;
        $this->httpParameters = $_httpParameters;
        $this->dbTable = $_dbTable;
        $this->dbTableCustomColumns = $_dbTableCustomColumns;
        $this->dbTableStatusColumns = $_dbTableStatusColumns;
        $this->dbTableHashColumns = $_dbTableHashColumns;
        $this->phpClassName = $_phpClassName;
    }
    
    public function getHttpURL() {
        return $this->httpURL;        
    }
    
    public function getHttpMethod() {
        return $this->httpMethod;        
    }
    
    public function getTrackingParameters( $tracking ) {
        $return = array();
        if ($tracking instanceof mpTracking) {
            foreach($this->httpParameters as $key => $val) {
                $result = "";
                eval('$result = ' . $val . ';');
                $return[$key] = $result;
            }    
        }
        return $return;
    }
    
    public function setTrackingInfo($array) {        
        $paramsCount = count($array);
        if( $paramsCount == count($this->dbTableCustomColumns) ) {
            $i = 0;
            foreach($this->dbTableCustomColumns as $dbColumn) {
                $this->dbTableCustomColumnsValues[$dbColumn] = $array[$i++];
            }
        }
    }
    
    public function getTrackingHash() {        
        $hashOrigin = ".";
        foreach($this->dbTableHashColumns as $columnName) {
            $hashOrigin .= $this->dbTableCustomColumnsValues[$columnName];
        }
        $hashOrigin .= ".";
        
        $hashReturn = md5($hashOrigin);
        return $hashReturn;
    }
    
    public function getDatabaseTable() {
        return $this->dbTable;
    }
    
    public function getCustomColumnsValues() {
        $c = new mpConnect();
        $return = array();
        foreach($this->dbTableHashColumns as $columnName) {
            $return["track_{$columnName}"] = "'" . $c->escape($this->dbTableCustomColumnsValues[$columnName]) . "'";
        }        
        return $return;
    }
    
    public function getStatusColumnsValues() {
        $return = "";
        foreach($this->dbTableStatusColumns as $columnName) {
            $return .= $this->dbTableCustomColumnsValues[$columnName] . "\n";
        }        
        return $return;
    }
    
    public function getPhpClassName() {
        return $this->phpClassName;
    }
        

}

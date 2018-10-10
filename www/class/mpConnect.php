<?php

/*
 * Connect.php (UTF-8)
 * Desenvolvido por Elisson Silva em 20/06/2014
 */

class mpConnect {
    
    static private $mysqli;
    private $result;
    private $resultRow;
    private $resultRowPosition;
    
    function __construct() {
               
        $this->result = [];
        $this->resultRow = [];
        $this->resultRowPosition = [];
        
    }
    
    public function __destruct() {
        if( is_object(self::$mysqli) ) {
            self::$mysqli->close();
            self::$mysqli = NULL;
        }
    }
    
    private function connect() {
        
        if( is_object(self::$mysqli) ) {
            return self::$mysqli;
        }

        $cfg = new mpConfig();
        self::$mysqli = new mysqli( $cfg->bd_host, $cfg->bd_user, $cfg->bd_pass, $cfg->bd_dbname);
        if (self::$mysqli->connect_error) {
            die('Connect Error (' . self::$mysqli->connect_errno . ') ' . self::$mysqli->connect_error);
        }
        
        return self::$mysqli;
    }
    
    public function query($query, $position = 0)
    {
        $conn = $this->connect();
        $this->result[$position] = $conn->query($query);
        if (!$this->result[$position]) {
            die('Could not run the MySQL query: ' . $conn->error);
        }
        $this->resultRowPosition[$position] = 0;
        return $this->result[$position];
    }
    
    public function escape($string)
    {
        $conn = $this->connect();
        return $conn->real_escape_string($string);
    }
    
    public function getColumnValue($columnNameOrPosition, $position = 0) {
        
        return $this->resultRow[$position][$columnNameOrPosition];
        
    }
    
    public function nextRow($position = 0)
    {
        $this->resultRow[$position] = $this->result[$position]->fetch_array(MYSQLI_BOTH);
        $this->resultRowPosition[$position]++;
        return $this->resultRow[$position];
    }
    
    public function numberRows($position = 0)
    {
        return $this->result[$position]->num_rows;
    }
    
    public function buildQuery( $type, $table, $columns, $where = "")
    {
        $query = "";
        switch($type)
        {
            case "UPDATE":
                $total = count($columns);
                $query = "update {$table} set ";
                $count = 0;
                foreach($columns as $nome => $valor)
                {
                    $query .= "{$nome} = {$valor}";
                    if(++$count < $total) {
                        $query .= ", ";
                    }
                }
                $query .= " where {$where} ";
                $query .= ";";
                break;
                
            case "INSERT":
                $total = count($columns);
                $query = "insert into {$table} (";
                $count = 0;
                foreach($columns as $nome => $valor)
                {
                    $query .= "{$nome}";
                    if(++$count < $total) {
                        $query .= ", ";
                    }
                }
                $query .= ") values (";
                $count = 0;
                foreach($columns as $nome => $valor)
                {
                    $query .= "{$valor}";
                    if(++$count < $total) {
                        $query .= ", ";
                    }
                }
                $query .= ");";
                break;
                
            case "DELETE":
                $query .= "delete from {$table} ";
                $query .= " where {$where} ";
                break;
                
        }
        
        return $query;
    }
    
}

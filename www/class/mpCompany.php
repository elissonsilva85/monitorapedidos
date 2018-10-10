<?php

/*
 * Project.php (UTF-8)
 * Desenvolvido por Elisson Silva em 21/07/2014
 */

class mpCompany {
    private $company_id;
    private $name;
    private $description;
    private $link_site;
    private $tracking_class;
    private $active;
    private $created_date;
    private $created_by;
    private $approved_date;
    private $approved_by;
    
    function __construct() {
        $paramsQtde = func_num_args();
        
        if( $paramsQtde == 1 )
        {
            $this->loadCompany(func_get_arg(0));
        }
        else if( $paramsQtde > 1 )
        {
            $this->company_id = func_get_arg(0);
            $this->name = func_get_arg(1);
            $this->description = func_get_arg(2);
            $this->link_site = func_get_arg(3);
            $this->tracking_class = func_get_arg(4);
            $this->active = func_get_arg(5);
            $this->created_date = func_get_arg(6);
            $this->created_by = func_get_arg(7);
            $this->approved_date = func_get_arg(8);
            $this->approved_by = func_get_arg(9);
        }
    }
    
    private function loadCompany($codigo) {
        
        $c = new mpConnect();
        
        $codigo = $c->escape($codigo);
        if( $c->query("select * from companies where company_id = {$codigo}") )
        {
            $c->nextRow();
            $this->company_id = $c->getColumnValue("company_id");
            $this->name = $c->getColumnValue("name");
            $this->description = $c->getColumnValue("description");
            $this->link_site = $c->getColumnValue("link_site");
            $this->tracking_class = $c->getColumnValue("tracking_class");
            $this->active = $c->getColumnValue("active");
            $this->created_date = $c->getColumnValue("created_date");
            $this->created_by = $c->getColumnValue("created_by");
            $this->approved_date = $c->getColumnValue("approved_date");
            $this->approved_by = $c->getColumnValue("approved_by");        
        }
    }
    
    public function getId() {
        return $this->company_id;
    }

    public function getName() {
        return $this->name;
    }

    public function getActivated() {
        return $this->active;
    }

    public function getCreator() {
        if($this->created_by instanceof mpUser)
            return $this->created_by;
        
        $this->created_by = new mpUser($this->created_by);
        return $this->created_by;
    }
    
    public function getTrackingClass()
    {
        if($this->tracking_class instanceof mpCompanyTracking)
            return $this->tracking_class;
        
        $this->tracking_class = unserialize( base64_decode($this->tracking_class) );
        return $this->tracking_class;
    }
    
    public function saveTrackingInfo( $trackingId ) {
        
        $c = new mpConnect();
        $companyTrackingClass = $this->getTrackingClass();
        
        $table = $companyTrackingClass->getDatabaseTable();
        $hash  = $companyTrackingClass->getTrackingHash();
        
        $return = "...";
        
        // Procura se já existe um registro com as informações que serão adicionadas
        $query = "select register_id from {$table} where track_hash = '{$hash}' and tracking_id = '{$trackingId}';";
        $c->query($query);
        if( $c->nextRow() )
        {
            // Se ja tiver um registro com o mesmo hash, então faz o update
            // UPDATE
            $registerId = $c->getColumnValue("register_id");
            $colunas["last_update_date"] = "str_to_date('".date("d/m/Y H:i:s")."','%d/%m/%Y %H:%i:%s')";
            $query = $c->buildQuery("UPDATE", $table, $colunas, "register_id = {$registerId}");
            
            $return = "UPDATE";
        }
        else
        {
            // Se não tiver o registro, então faz o insert
            // INSERT
            $colunas = $companyTrackingClass->getCustomColumnsValues();
            $colunas["tracking_id"]      = "{$trackingId}";
            $colunas["track_hash"]       = "'{$hash}'";
            $colunas["created_date"]     = "str_to_date('".date("d/m/Y H:i:s")."','%d/%m/%Y %H:%i:%s')";
            $colunas["last_update_date"] = "str_to_date('".date("d/m/Y H:i:s")."','%d/%m/%Y %H:%i:%s')";
            $query = $c->buildQuery("INSERT", $table, $colunas);
            
            // Se gerou INSERT, então insere na fila de email
            $return = "INSERT";
        }
        
        //Executa o Insert/Update
        $c->query($query);
        return $return;
        
    }
    
}

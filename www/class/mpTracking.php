<?php

/*
 * Project.php (UTF-8)
 * Desenvolvido por Elisson Silva em 21/07/2014
 */

class mpTracking {
    private $tracking_id;
    private $order_id;
    private $tracking_no;
    private $last_update_date;
    private $last_update_info;
    private $last_error;
    private $company_id;
    private $active;
    private $created_date;
    private $created_by;
    
    private $order;
    
    function __construct() {
        $paramsQtde = func_num_args();
        
        if( $paramsQtde == 1 )
        {
            $this->loadTracking(func_get_arg(0));
        }
        else if( $paramsQtde > 1 )
        {
            $this->tracking_id = func_get_arg(0);
            $this->order_id = func_get_arg(1);
            $this->tracking_no = func_get_arg(2);
            $this->last_update_date = func_get_arg(3);
            $this->last_update_info = func_get_arg(4);
            $this->last_error = func_get_arg(5);
            $this->company_id = func_get_arg(6);
            $this->active = func_get_arg(7);
            $this->created_date = func_get_arg(8);
            $this->created_by = func_get_arg(9);
        }
    }
    
    private function loadTracking($codigo) {
        
        $c = new mpConnect();
        
        $codigo = $c->escape($codigo);
        if( $c->query("select * from trackings where tracking_id = {$codigo}") )
        {
            $c->nextRow();
            $this->tracking_id = $c->getColumnValue("tracking_id");
            $this->order_id = $c->getColumnValue("order_id");
            $this->tracking_no = $c->getColumnValue("tracking_no");
            $this->last_update_date = $c->getColumnValue("last_update_date");
            $this->last_update_info = $c->getColumnValue("last_update_info");
            $this->last_error = $c->getColumnValue("last_error");
            $this->company_id = $c->getColumnValue("company_id");
            $this->active = $c->getColumnValue("active");
            $this->created_date = $c->getColumnValue("created_date");
            $this->created_by = $c->getColumnValue("created_by");
        }
    }
    
    public function getId() {
        return $this->tracking_id;
    }
    
    public function getNumber() {
        return $this->tracking_no;
    }

    public function getName() {
        return $this->name;
    }

    public function getActivated() {
        return $this->active;
    }

    public function getCreator() {
        if(is_object($this->created_by) )
            return $this->created_by;
        
        $this->created_by = new mpUser($this->created_by);
        return $this->created_by;
    }
    
    public function getOrderId() {
        return $this->order_id;
    }
    
    public function getOrder() {
        if(is_object($this->order) )
            return $this->order;
        
        $this->order = new mpOrder($this->order_id);
        return $this->order;
    }
    
}
